<?php

namespace App\Service;

use App\Entity\ShareToken;
use App\Repository\ShareTokenRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ShareTokenService
{
    public function __construct(
        private readonly ShareTokenRepository $shareTokenRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function createShareLink(ShareToken $shareToken): string
    {
        return $this->urlGenerator->generate('app_share_view', [
            'token' => $shareToken->getToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function validateToken(string $token): ?ShareToken
    {
        return $this->shareTokenRepository->findByToken($token);
    }

    public function createShareToken(string $label, ?\App\Entity\User $createdBy = null): ShareToken
    {
        $shareToken = new ShareToken();
        $shareToken->setToken($this->generateToken());
        $shareToken->setLabel($label);
        $shareToken->setCreatedBy($createdBy);

        $this->shareTokenRepository->save($shareToken, true);

        return $shareToken;
    }
}
