<?php

namespace App\Controller;

use App\Entity\Maturity;
use App\Form\MaturityType;
use App\Repository\MaturityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MaturityController extends AbstractController
{
    #[Route('/admin/maturity', name: 'app_admin_maturity')]
    public function list(MaturityRepository $maturityRepository): Response
    {
        $maturitys = $maturityRepository->findAll();

        return $this->render('maturity/list.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
            'title' => 'Liste des Maturités',
            'routesubmit' => 'app_admin_maturity_submit',
            'routeupdate' => 'app_admin_maturity_update',
            'maturitys' => $maturitys,
        ]);
    }

    #[Route('/admin/maturity/submit', name: 'app_admin_maturity_submit')]
    public function submit(Request $request, EntityManagerInterface $em): Response
    {
        $maturity = new Maturity();

        $form = $this->createForm(MaturityType::class, $maturity, ['mode' => 'submit']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($maturity);
            $em->flush();

            return $this->redirectToRoute('app_admin_maturity');
        }

        return $this->render('maturity/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
            'title' => 'Création Maturité',
            'routecancel' => 'app_admin_maturity',
            'routedelete' => 'app_admin_maturity_delete',
            'mode' => 'submit',
            'form' => $form,
        ]);
    }

    #[Route('/admin/maturity/update/{id}', name: 'app_admin_maturity_update')]
    public function update(int $id, Request $request, MaturityRepository $maturityRepository, EntityManagerInterface $em): Response
    {
        $maturity = $maturityRepository->find($id);
        if (!$maturity) {
            return $this->redirectToRoute('app_admin_maturity');
        }

        $form = $this->createForm(MaturityType::class, $maturity, ['mode' => 'update']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_admin_maturity');
        }

        return $this->render('maturity/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
            'title' => 'Modification Maturité = '.$maturity->getCode(),
            'routecancel' => 'app_admin_maturity',
            'routedelete' => 'app_admin_maturity_delete',
            'mode' => 'update',
            'form' => $form,
        ]);
    }

    #[Route('/admin/maturity/delete/{id}', name: 'app_admin_maturity_delete')]
    public function delete(int $id, MaturityRepository $maturityRepository, EntityManagerInterface $em): Response
    {
        $maturity = $maturityRepository->find($id);
        if (!$maturity) {
            return $this->redirectToRoute('app_admin_maturity');
        }

        try {
            $em->remove($maturity);
            $em->flush();
        } catch (\Exception $e) {
            $this->addflash('error', $e->getMessage());

            return $this->redirectToRoute('app_admin_maturity_update', ['id' => $id]);
        }

        return $this->redirectToRoute('app_admin_maturity');
    }
}
