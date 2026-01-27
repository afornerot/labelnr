<?php

namespace App\Controller;

use App\Entity\DMR;
use App\Entity\TIR;
use App\Enum\DMRTypeEnum;
use App\Repository\DMRRepository;
use App\Repository\MaturityRepository;
use App\Repository\PARepository;
use App\Repository\ThematicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    // Injection des dépendances via le constructeur (Promotion de propriété PHP 8)
    public function __construct(
        private readonly ThematicRepository $thematicRepository,
        private readonly PARepository $paRepository,
        private readonly MaturityRepository $maturityRepository,
        private readonly DMRRepository $dmrRepository,
        private readonly EntityManagerInterface $em,
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        // On utilise le template complet par défaut ici
        return $this->renderTableFragment('home/home.html.twig');
    }

    #[Route('/user/tir/updatematurity', name: 'app_tir_updatematurity', methods: ['POST'])]
    public function updateTirMaturity(Request $request): Response
    {
        $tirId = $request->request->get('tirId');
        $maturityId = $request->request->get('maturityId');

        $tir = $this->em->getRepository(TIR::class)->find($tirId);
        $maturity = $this->maturityRepository->find($maturityId);

        if ($tir && $maturity) {
            $tir->setMaturity($maturity);
            $this->em->flush();
        }

        return $this->renderTableFragment('home/table.html.twig');
    }

    #[Route('/user/tir/updatecomment', name: 'app_tir_updatecomment', methods: ['POST'])]
    public function updateTirComment(Request $request): Response
    {
        $tirId = $request->request->get('tirId');
        $comment = $request->request->get('comment');

        $tir = $this->em->getRepository(TIR::class)->find($tirId);
        if ($tir) {
            $tir->setComment($comment);
            $this->em->flush();
        }

        return $this->renderTableFragment('home/table.html.twig');
    }

    #[Route('/user/tir/submitdmr', name: 'app_tir_submitdmr', methods: ['POST'])]
    public function submitTirDmr(Request $request): Response
    {
        $tirId = $request->request->get('tirId');
        $type = $request->request->get('type');
        $comment = $request->request->get('comment');

        $tir = $this->em->getRepository(TIR::class)->find($tirId);
        if ($tir) {
            $dmr = new DMR();
            $dmr->setTir($tir);
            $dmr->setType(DMRTypeEnum::tryFrom($type));
            $dmr->setComment($comment);
            $this->em->persist($dmr);
            $this->em->flush();
        }

        return $this->renderTableFragment('home/table.html.twig');
    }

    #[Route('/user/tir/updatedmr', name: 'app_tir_updatedmr', methods: ['POST'])]
    public function updateTirDmr(Request $request): Response
    {
        $dmrId = $request->request->get('dmrId');
        $comment = $request->request->get('comment');

        $dmr = $this->dmrRepository->find($dmrId);
        if ($dmr) {
            $dmr->setComment($comment);
            $this->em->flush();
        }

        return $this->renderTableFragment('home/table.html.twig');
    }

    #[Route('/user/tir/deletedmr', name: 'app_tir_deletedmr', methods: ['POST'])]
    public function deleteTirDmr(Request $request): Response
    {
        $dmrId = $request->request->get('dmrId');

        $dmr = $this->dmrRepository->find($dmrId);
        if ($dmr) {
            $this->em->remove($dmr);
            $this->em->flush();
        }

        return $this->renderTableFragment('home/table.html.twig');
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
        $maturitys = $this->maturityRepository->findAll();
        $pas = $this->paRepository->findAll();

        $total = 0;
        $count = 0;
        foreach ($thematics as $thematic) {
            $total += $thematic->getScore() * $thematic->getLength();
            $count += $thematic->getLength();
        }

        $score = $count > 0 ? $total / $count : 0;

        return $this->render($template, [
            'pas' => $pas,
            'score' => $score,
            'maturitys' => $maturitys,
            'usemenu' => true,
            'usesidebar' => false,
        ]);
    }
}
