<?php

namespace App\Controller;

use App\Entity\Weekly;
use App\Form\WeeklyType;
use App\Repository\WeeklyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WeeklyController extends AbstractController
{
    public function __construct(
        private WeeklyRepository $weeklyRepository,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/admin/weekly', name: 'app_admin_weekly')]
    public function list(): Response
    {
        $weeklys = $this->weeklyRepository->findAll();

        return $this->render('weekly/list.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Liste des Weekly',
            'routesubmit' => 'app_admin_weekly_submit',
            'routeupdate' => 'app_admin_weekly_update',
            'weeklys' => $weeklys,
        ]);
    }

    #[Route('/admin/weekly/submit', name: 'app_admin_weekly_submit')]
    public function submit(Request $request): Response
    {
        $weekly = new Weekly();

        $form = $this->createForm(WeeklyType::class, $weekly, ['mode' => 'submit']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($weekly);
            $this->em->flush();

            return $this->redirectToRoute('app_admin_weekly');
        }

        return $this->render('weekly/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Création Weekly',
            'routecancel' => 'app_admin_weekly',
            'routedelete' => 'app_admin_weekly_delete',
            'mode' => 'submit',
            'form' => $form,
        ]);
    }

    #[Route('/admin/weekly/update/{id}', name: 'app_admin_weekly_update')]
    public function update(int $id, Request $request): Response
    {
        $weekly = $this->weeklyRepository->find($id);
        if (!$weekly) {
            return $this->redirectToRoute('app_admin_weekly');
        }

        $form = $this->createForm(WeeklyType::class, $weekly, ['mode' => 'update']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('app_admin_weekly');
        }

        return $this->render('weekly/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Modification Weekly = '.$weekly->getDate()->format('Y-m-d'),
            'routecancel' => 'app_admin_weekly',
            'routedelete' => 'app_admin_weekly_delete',
            'mode' => 'update',
            'form' => $form,
        ]);
    }

    #[Route('/admin/weekly/delete/{id}', name: 'app_admin_weekly_delete')]
    public function delete(int $id): Response
    {
        $weekly = $this->weeklyRepository->find($id);
        if (!$weekly) {
            return $this->redirectToRoute('app_admin_weekly');
        }

        try {
            $this->em->remove($weekly);
            $this->em->flush();
        } catch (\Exception $e) {
            $this->addflash('error', $e->getMessage());

            return $this->redirectToRoute('app_admin_weekly_update', ['id' => $id]);
        }

        return $this->redirectToRoute('app_admin_weekly');
    }
}
