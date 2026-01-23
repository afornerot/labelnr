<?php

namespace App\Controller;

use App\Repository\PARepository;
use App\Repository\ThematicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(ThematicRepository $thematicRepository, PARepository $paRepository): Response
    {
        $thematics = $thematicRepository->findAll();
        $pas = $paRepository->findAll();

        $total = 0;
        $count = 0;
        foreach ($thematics as $thematic) {
            $total += $thematic->getScore() * $thematic->getLength();
            $count += $thematic->getLength();
        }

        $score = $count > 0 ? $total / $count : null;

        return $this->render('home/home.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'pas' => $pas,
            'score' => $score,
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('home/blank.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
        ]);
    }
}
