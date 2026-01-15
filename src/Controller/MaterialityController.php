<?php

namespace App\Controller;

use App\Entity\Materiality;
use App\Form\MaterialityType;
use App\Repository\MaterialityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MaterialityController extends AbstractController
{
    #[Route('/admin/materiality', name: 'app_admin_materiality')]
    public function list(MaterialityRepository $materialityRepository): Response
    {
        $materialitys = $materialityRepository->findAll();

        return $this->render('materiality/list.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
            'title' => 'Liste des Matérialités',
            'routesubmit' => 'app_admin_materiality_submit',
            'routeupdate' => 'app_admin_materiality_update',
            'materialitys' => $materialitys,
        ]);
    }

    #[Route('/admin/materiality/submit', name: 'app_admin_materiality_submit')]
    public function submit(Request $request, EntityManagerInterface $em): Response
    {
        $materiality = new Materiality();

        $form = $this->createForm(MaterialityType::class, $materiality, ['mode' => 'submit']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($materiality);
            $em->flush();

            return $this->redirectToRoute('app_admin_materiality');
        }

        return $this->render('materiality/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
            'title' => 'Création Matérialité',
            'routecancel' => 'app_admin_materiality',
            'routedelete' => 'app_admin_materiality_delete',
            'mode' => 'submit',
            'form' => $form,
        ]);
    }

    #[Route('/admin/materiality/update/{id}', name: 'app_admin_materiality_update')]
    public function update(int $id, Request $request, MaterialityRepository $materialityRepository, EntityManagerInterface $em): Response
    {
        $materiality = $materialityRepository->find($id);
        if (!$materiality) {
            return $this->redirectToRoute('app_admin_materiality');
        }

        $form = $this->createForm(MaterialityType::class, $materiality, ['mode' => 'update']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_admin_materiality');
        }

        return $this->render('materiality/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
            'title' => 'Modification Matérialité = '.$materiality->getCode(),
            'routecancel' => 'app_admin_materiality',
            'routedelete' => 'app_admin_materiality_delete',
            'mode' => 'update',
            'form' => $form,
        ]);
    }

    #[Route('/admin/materiality/delete/{id}', name: 'app_admin_materiality_delete')]
    public function delete(int $id, MaterialityRepository $materialityRepository, EntityManagerInterface $em): Response
    {
        $materiality = $materialityRepository->find($id);
        if (!$materiality) {
            return $this->redirectToRoute('app_admin_materiality');
        }

        try {
            $em->remove($materiality);
            $em->flush();
        } catch (\Exception $e) {
            $this->addflash('error', $e->getMessage());

            return $this->redirectToRoute('app_admin_materiality_update', ['id' => $id]);
        }

        return $this->redirectToRoute('app_admin_materiality');
    }
}
