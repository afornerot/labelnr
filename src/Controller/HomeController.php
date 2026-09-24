<?php

namespace App\Controller;

use App\Repository\ActionPlanRepository;
use App\Repository\MaterialityRepository;
use App\Repository\PARepository;
use App\Repository\ThematicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly ThematicRepository $thematicRepository,
        private readonly PARepository $paRepository,
        private readonly MaterialityRepository $materialityRepository,
        private readonly ActionPlanRepository $actionPlanRepository,
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->renderTableFragment('home/home.html.twig');
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('home/blank.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
        ]);
    }

    private function renderTableFragment(string $template): Response
    {
        $thematics = $this->thematicRepository->findAll();
        $pas = $this->paRepository->findAll();
        $materialities = $this->materialityRepository->findAll();
        $actionPlans = $this->actionPlanRepository->findAll();

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

        return $this->render($template, [
            'groupedPas' => $groupedPas,
            'score' => $score,
            'materialities' => $materialities,
            'actionPlans' => $actionPlans,
            'usemenu' => true,
            'usesidebar' => false,
        ]);
    }
}
