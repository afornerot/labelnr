<?php

namespace App\Security;

use App\Repository\ShareTokenRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ShareTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(private ShareTokenRepository $shareTokenRepository)
    {
    }

    public function supports(Request $request): ?bool
    {
        if (preg_match('#^/share/([a-f0-9]{64})$#', $request->getPathInfo())) {
            return true;
        }

        if ($request->query->has('shareToken')) {
            return true;
        }

        if ($request->getSession()->has('_share_token')) {
            return true;
        }

        return false;
    }

    public function authenticate(Request $request): Passport
    {
        $token = null;

        if (preg_match('#^/share/([a-f0-9]{64})$#', $request->getPathInfo(), $matches)) {
            $token = $matches[1];
        } elseif ($request->query->has('shareToken')) {
            $token = $request->query->get('shareToken');
        } elseif ($request->getSession()->has('_share_token')) {
            $token = $request->getSession()->get('_share_token');
        }

        if (!$token) {
            throw new AuthenticationException('No share token provided.');
        }

        $shareToken = $this->shareTokenRepository->findByToken($token);

        if (!$shareToken) {
            throw new AuthenticationException('Invalid share token.');
        }

        $shareUser = new InMemoryUser('share_'.$token, null, ['ROLE_SHARE', 'ROLE_USER']);

        return new SelfValidatingPassport(new UserBadge($shareUser->getUserIdentifier(), fn () => $shareUser));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?\Symfony\Component\HttpFoundation\Response
    {
        if (preg_match('#^/share/([a-f0-9]{64})$#', $request->getPathInfo())) {
            $tokenValue = str_replace('share_', '', $token->getUserIdentifier());
            $request->getSession()->set('_share_token', $tokenValue);
        }

        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?\Symfony\Component\HttpFoundation\Response
    {
        throw $exception;
    }
}
