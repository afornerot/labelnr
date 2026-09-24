<?php

namespace App\Controller;

use App\Entity\ActionPlan;
use App\Form\ActionPlanType;
use App\Repository\ActionPlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ActionPlanController extends AbstractController
{
    public function __construct(
        private ActionPlanRepository $actionPlanRepository,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/action-plan', name: 'app_admin_action_plan')]
    public function list(): Response
    {
        $actionPlans = $this->actionPlanRepository->findAll();

        return $this->render('action_plan/list.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Plan d\'action',
            'routesubmit' => 'app_admin_action_plan_submit',
            'routeupdate' => 'app_admin_action_plan_update',
            'actionPlans' => $actionPlans,
        ]);
    }

    #[Route('/action-plan/submit', name: 'app_admin_action_plan_submit')]
    public function submit(Request $request): Response
    {
        $actionPlan = new ActionPlan();

        $form = $this->createForm(ActionPlanType::class, $actionPlan, ['mode' => 'submit']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($actionPlan);
            $this->em->flush();

            return $this->redirectToRoute('app_admin_action_plan');
        }

        return $this->render('action_plan/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Création ActionPlan',
            'routecancel' => 'app_admin_action_plan',
            'routedelete' => 'app_admin_action_plan_delete',
            'mode' => 'submit',
            'form' => $form,
        ]);
    }

    #[Route('/action-plan/update/{id}', name: 'app_admin_action_plan_update')]
    public function update(int $id, Request $request): Response
    {
        $actionPlan = $this->actionPlanRepository->find($id);
        if (!$actionPlan) {
            return $this->redirectToRoute('app_admin_action_plan');
        }

        $form = $this->createForm(ActionPlanType::class, $actionPlan, ['mode' => 'update']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $actionPlan->setUpdatedAt(new \DateTime());
            $this->em->flush();

            return $this->redirectToRoute('app_admin_action_plan');
        }

        return $this->render('action_plan/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Modification ActionPlan = '.$actionPlan->getTitle(),
            'routecancel' => 'app_admin_action_plan',
            'routedelete' => 'app_admin_action_plan_delete',
            'mode' => 'update',
            'form' => $form,
        ]);
    }

    #[Route('/action-plan/delete/{id}', name: 'app_admin_action_plan_delete')]
    public function delete(int $id): Response
    {
        $actionPlan = $this->actionPlanRepository->find($id);
        if (!$actionPlan) {
            return $this->redirectToRoute('app_admin_action_plan');
        }

        try {
            $this->em->remove($actionPlan);
            $this->em->flush();
        } catch (\Exception $e) {
            $this->addflash('error', $e->getMessage());

            return $this->redirectToRoute('app_admin_action_plan_update', ['id' => $id]);
        }

        return $this->redirectToRoute('app_admin_action_plan');
    }
}
