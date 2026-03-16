<?php

namespace App\Controller;

use App\Repository\PARepository;
use App\Repository\ThematicRepository;
use App\Repository\TIRRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RestController extends AbstractController
{
    public function __construct(
        private readonly ThematicRepository $thematicRepository,
        private readonly PARepository $paRepository,
        private readonly TIRRepository $tirRepository,
        private readonly ParameterBagInterface $params,
    ) {
    }

    #[Route('/rest/dossier', name: 'app_api_rest_dossier', methods: ['GET'])]
    public function getDossier(Request $request): JsonResponse
    {
        $apiKey = $request->query->get('apikey');
        $appSecret = $this->params->get('appSecret');

        if ($apiKey !== $appSecret) {
            return $this->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $thematics = $this->thematicRepository->findAll();
        $pas = $this->paRepository->findAll();
        $tirs = $this->tirRepository->findAll();

        $data = [
            'global_score' => 0,
            'thematics' => [],
        ];

        $totalScore = 0;
        $totalWeight = 0;

        foreach ($thematics as $thematic) {
            $thematicData = [
                'id' => $thematic->getId(),
                'code' => $thematic->getCode(),
                'title' => $thematic->getTitle(),
                'score' => $thematic->getScore(),
                'pas' => [],
            ];

            $totalScore += $thematic->getScore() * $thematic->getLength();
            $totalWeight += $thematic->getLength();

            // Filter PAs for this thematic
            $thematicPas = array_filter($pas, fn ($pa) => $pa->getThematic() === $thematic);

            foreach ($thematicPas as $pa) {
                $paData = [
                    'id' => $pa->getId(),
                    'code' => $pa->getCode(),
                    'title' => $pa->getTitle(),
                    'score' => $pa->getScore(),
                    'tirs' => [],
                ];

                // Filter TIRs for this PA
                $paTirs = array_filter($tirs, fn ($tir) => $tir->getPa() === $pa);

                foreach ($paTirs as $tir) {
                    $maturity = $tir->getMaturity();
                    $paData['tirs'][] = [
                        'id' => $tir->getId(),
                        'code' => $tir->getCode(),
                        'title' => $tir->getTitle(),
                        'comment' => $tir->getComment(),
                        'maturity' => $maturity ? [
                            'id' => $maturity->getId(),
                            'code' => $maturity->getCode(),
                            'title' => $maturity->getTitle(),
                            'value' => $maturity->getValue(),
                        ] : null,
                    ];
                }

                $thematicData['pas'][] = $paData;
            }

            $data['thematics'][] = $thematicData;
        }

        $data['global_score'] = $totalWeight > 0 ? $totalScore / $totalWeight : 0;

        return $this->json($data);
    }
}
