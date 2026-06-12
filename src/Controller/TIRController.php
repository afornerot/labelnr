<?php

namespace App\Controller;

use App\Entity\DMR;
use App\Enum\DMRTypeEnum;
use App\Form\DMRType;
use App\Form\TIRType;
use App\Repository\DMRRepository;
use App\Repository\MaturityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TIRController extends AbstractController
{
    public function __construct(
        private readonly MaturityRepository $maturityRepository,
        private readonly DMRRepository $dmrRepository,
        private readonly EntityManagerInterface $em,
    ) {
    }

    #[Route('/tir/{id}', name: 'app_tir_show')]
    public function show(int $id): Response
    {
        $tir = $this->em->getRepository(\App\Entity\TIR::class)->find($id);
        
        if (!$tir) {
            throw $this->createNotFoundException('TIR non trouvé');
        }

        return $this->render('tir/show.html.twig', [
            'tir' => $tir,
            'usemenu' => true,
            'usesidebar' => false,
        ]);
    }

    #[Route('/tir/{id}/edit', name: 'app_tir_edit')]
    public function edit(int $id, Request $request): Response
    {
        $tir = $this->em->getRepository(\App\Entity\TIR::class)->find($id);
        
        if (!$tir) {
            throw $this->createNotFoundException('TIR non trouvé');
        }

        $commentForm = $this->createForm(TIRType::class, $tir);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Commentaire enregistré.');
            return $this->redirectToRoute('app_tir_edit', ['id' => $id]);
        }

        $dmrForms = [];
        foreach (DMRTypeEnum::cases() as $type) {
            $dmr = new DMR();
            $dmr->setType($type);
            $dmrForms[$type->value] = $this->createForm(DMRType::class, $dmr, [
                'action' => $this->generateUrl('app_tir_edit_dmr_add', ['id' => $id]),
                'submit_label' => 'Ajouter',
            ]);
        }

        $editDmrForms = [];
        foreach ($tir->getDmrs() as $dmr) {
            $editDmrForms[$dmr->getId()] = $this->createForm(DMRType::class, $dmr, [
                'action' => $this->generateUrl('app_dmr_edit', ['dmrId' => $dmr->getId()]),
                'submit_label' => 'Enregistrer',
            ]);
        }

        return $this->render('tir/edit.html.twig', [
            'tir' => $tir,
            'maturitys' => $this->maturityRepository->findAll(),
            'commentForm' => $commentForm->createView(),
            'dmrForms' => array_map(fn($f) => $f->createView(), $dmrForms),
            'editDmrForms' => array_map(fn($f) => $f->createView(), $editDmrForms),
            'usemenu' => true,
            'usesidebar' => false,
        ]);
    }

    #[Route('/tir/{id}/edit/maturity', name: 'app_tir_edit_maturity', methods: ['POST'])]
    public function updateMaturity(int $id, Request $request): Response
    {
        $tir = $this->em->getRepository(\App\Entity\TIR::class)->find($id);
        
        if (!$tir) {
            throw $this->createNotFoundException('TIR non trouvé');
        }

        $maturityId = $request->request->get('maturityId');
        $maturity = $this->maturityRepository->find($maturityId);

        if ($maturity) {
            $tir->setMaturity($maturity);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_tir_edit', ['id' => $id]);
    }

    #[Route('/tir/{id}/edit/comment', name: 'app_tir_edit_comment', methods: ['POST'])]
    public function updateComment(int $id, Request $request): Response
    {
        $tir = $this->em->getRepository(\App\Entity\TIR::class)->find($id);
        
        if (!$tir) {
            throw $this->createNotFoundException('TIR non trouvé');
        }

        $comment = $request->request->get('comment');
        $tir->setComment($comment);
        $this->em->flush();

        return $this->redirectToRoute('app_tir_edit', ['id' => $id]);
    }

    #[Route('/tir/{id}/edit/dmr', name: 'app_tir_edit_dmr_add', methods: ['POST'])]
    public function addDmr(int $id, Request $request): Response
    {
        $tir = $this->em->getRepository(\App\Entity\TIR::class)->find($id);
        
        if (!$tir) {
            throw $this->createNotFoundException('TIR non trouvé');
        }

        $type = $request->request->get('type');
        $comment = $request->request->get('comment');

        $dmr = new DMR();
        $dmr->setTir($tir);
        $dmr->setType(DMRTypeEnum::tryFrom($type));
        $dmr->setComment($comment);
        $this->em->persist($dmr);
        $this->em->flush();

        return $this->redirectToRoute('app_tir_edit', ['id' => $id]);
    }

    #[Route('/dmr/{dmrId}/edit', name: 'app_dmr_edit', methods: ['POST'])]
    public function editDmr(int $dmrId, Request $request): Response
    {
        $dmr = $this->dmrRepository->find($dmrId);
        
        if (!$dmr) {
            throw $this->createNotFoundException('DMR non trouvé');
        }

        $comment = $request->request->get('comment');
        $dmr->setComment($comment);
        $this->em->flush();

        return $this->redirectToRoute('app_tir_edit', ['id' => $dmr->getTir()->getId()]);
    }

    #[Route('/dmr/{dmrId}/delete', name: 'app_dmr_delete', methods: ['POST'])]
    public function deleteDmr(int $dmrId): Response
    {
        $dmr = $this->dmrRepository->find($dmrId);
        
        if (!$dmr) {
            throw $this->createNotFoundException('DMR non trouvé');
        }

        $tirId = $dmr->getTir()->getId();
        $this->em->remove($dmr);
        $this->em->flush();

        return $this->redirectToRoute('app_tir_edit', ['id' => $tirId]);
    }
}
