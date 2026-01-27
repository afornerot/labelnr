<?php

namespace App\Controller;

use App\Repository\MaturityRepository;
use App\Repository\PARepository;
use App\Repository\ThematicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly ThematicRepository $thematicRepository,
        private readonly PARepository $paRepository,
        private readonly MaturityRepository $maturityRepository,
        private readonly ParameterBagInterface $params,
    ) {
    }

    #[Route('/api/print', name: 'app_api_print', methods: ['GET'])]
    public function print(Request $request): Response
    {
        $apiKey = $request->query->get('apikey');
        $level = $request->query->get('level', '1');
        $title = $request->query->get('title', 'Evaluation');
        $appSecret = $this->params->get('appSecret');

        if ($apiKey !== $appSecret) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $thematics = $this->thematicRepository->findAll();
        $pas = $this->paRepository->findAll();

        $total = 0;
        $count = 0;
        foreach ($thematics as $thematic) {
            $total += $thematic->getScore() * $thematic->getLength();
            $count += $thematic->getLength();
        }

        $score = $count > 0 ? $total / $count : 0;

        $content = $this->renderView('api/print.md.twig', [
            'pas' => $pas,
            'score' => $score,
            'level' => str_repeat('#', $level - 1),
            'title' => $title,
        ]);

        return new Response($content, 200, ['Content-Type' => 'text/markdown']);
    }
}
