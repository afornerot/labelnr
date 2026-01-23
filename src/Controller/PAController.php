<?php

namespace App\Controller;

use App\Entity\PA;
use App\Form\PAType;
use App\Repository\PARepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PAController extends AbstractController
{
    #[Route('/admin/pa', name: 'app_admin_pa')]
    public function list(PARepository $paRepository): Response
    {
        $pas = $paRepository->findAll();

        return $this->render('pa/list.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
            'title' => 'Liste des PA',
            'routesubmit' => 'app_admin_pa_submit',
            'routeupdate' => 'app_admin_pa_update',
            'pas' => $pas,
        ]);
    }

    #[Route('/admin/pa/submit', name: 'app_admin_pa_submit')]
    public function submit(Request $request, EntityManagerInterface $em): Response
    {
        $pa = new PA();

        $form = $this->createForm(PAType::class, $pa, ['mode' => 'submit']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pa);
            $em->flush();

            return $this->redirectToRoute('app_admin_pa');
        }

        return $this->render('pa/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
            'title' => 'Création PA',
            'routecancel' => 'app_admin_pa',
            'routedelete' => 'app_admin_pa_delete',
            'mode' => 'submit',
            'form' => $form,
        ]);
    }

    #[Route('/admin/pa/update/{id}', name: 'app_admin_pa_update')]
    public function update(int $id, Request $request, PARepository $paRepository, EntityManagerInterface $em): Response
    {
        $pa = $paRepository->find($id);
        if (!$pa) {
            return $this->redirectToRoute('app_admin_pa');
        }

        $form = $this->createForm(PAType::class, $pa, ['mode' => 'update']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_admin_pa');
        }

        return $this->render('pa/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
            'title' => 'Modification PA = '.$pa->getCode(),
            'routecancel' => 'app_admin_pa',
            'routedelete' => 'app_admin_pa_delete',
            'mode' => 'update',
            'form' => $form,
        ]);
    }

    #[Route('/admin/pa/delete/{id}', name: 'app_admin_pa_delete')]
    public function delete(int $id, PARepository $paRepository, EntityManagerInterface $em): Response
    {
        $pa = $paRepository->find($id);
        if (!$pa) {
            return $this->redirectToRoute('app_admin_pa');
        }

        try {
            $em->remove($pa);
            $em->flush();
        } catch (\Exception $e) {
            $this->addflash('error', $e->getMessage());

            return $this->redirectToRoute('app_admin_pa_update', ['id' => $id]);
        }

        return $this->redirectToRoute('app_admin_pa');
    }
}
