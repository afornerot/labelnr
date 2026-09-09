<?php

namespace App\Controller;

use App\Repository\ShareTokenRepository;
use App\Service\ShareTokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/share-token')]
class ShareTokenController extends AbstractController
{
    public function __construct(
        private readonly ShareTokenService $shareTokenService,
        private readonly ShareTokenRepository $shareTokenRepository,
    ) {
    }

    #[Route('/', name: 'app_admin_share_token')]
    public function index(): Response
    {
        return $this->render('share_token/index.html.twig', [
            'usemenu' => true,
            'usesidebar' => true,
            'tokens' => $this->shareTokenRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/create', name: 'app_admin_share_token_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $label = $request->request->get('label', 'Lien de partage');

        $this->shareTokenService->createShareToken($label, $this->getUser());

        $this->addFlash('success', 'Lien de partage créé avec succès.');

        return $this->redirectToRoute('app_admin_share_token');
    }

    #[Route('/delete/{id}', name: 'app_admin_share_token_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $token = $this->shareTokenRepository->find($id);

        if ($token) {
            $this->shareTokenRepository->remove($token, true);
            $this->addFlash('success', 'Lien de partage supprimé.');
        }

        return $this->redirectToRoute('app_admin_share_token');
    }
}
