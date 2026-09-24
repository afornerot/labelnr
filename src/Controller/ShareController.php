<?php

namespace App\Controller;

use App\Repository\MaterialityRepository;
use App\Repository\PARepository;
use App\Repository\ThematicRepository;
use App\Service\ShareTokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ShareController extends AbstractController
{
    public function __construct(
        private readonly ShareTokenService $shareTokenService,
        private readonly ThematicRepository $thematicRepository,
        private readonly PARepository $paRepository,
        private readonly MaterialityRepository $materialityRepository,
    ) {
    }

    #[Route('/share/{token}', name: 'app_share_view')]
    public function view(string $token, Request $request): Response
    {
        $shareToken = $this->shareTokenService->validateToken($token);

        if (!$shareToken) {
            throw $this->createNotFoundException('Lien de partage invalide ou expiré.');
        }

        $request->getSession()->set('_share_token', $token);

        $thematics = $this->thematicRepository->findAll();
        $pas = $this->paRepository->findAll();
        $materialities = $this->materialityRepository->findAll();

        $groupedPas = [];
        foreach ($pas as $pa) {
            $thematicCode = $pa->getThematic()->getCode();
            if (!isset($groupedPas[$thematicCode])) {
                $groupedPas[$thematicCode] = [
                    'thematic' => $pa->getThematic(),
                    'pas' => [],
                ];
            }
            $groupedPas[$thematicCode]['pas'][] = $pa;
        }

        $total = 0;
        $count = 0;
        foreach ($thematics as $thematic) {
            $total += $thematic->getScore() * $thematic->getLength();
            $count += $thematic->getLength();
        }

        $score = $count > 0 ? $total / $count : 0;

        return $this->render('share/index.html.twig', [
            'groupedPas' => $groupedPas,
            'score' => $score,
            'materialities' => $materialities,
            'usemenu' => false,
            'usesidebar' => false,
            'shareLabel' => $shareToken->getLabel(),
            'shareToken' => $token,
            'readOnly' => true,
        ]);
    }
}
