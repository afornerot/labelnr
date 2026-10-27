<?php

namespace App\Security;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Point d'entrée du firewall principal : redirige vers le fournisseur OIDC en
 * mode OIDC, sinon vers le formulaire de login (comportement historique de
 * form_login pour SQL et CAS).
 */
class AuthGuardEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        #[Autowire(param: 'modeAuth')]
        private string $modeAuth,
        private OidcClient $oidcClient,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        // Retour du fournisseur sans état en session : le cookie a été perdu
        // (autre nom de cookie, changement d'hôte, expiration). Sans ce
        // garde-fou, on relance une autorisation et le navigateur boucle.
        if ('OIDC' === $this->modeAuth
            && $request->query->has('code')
            && !$request->getSession()->has(OidcClient::SESSION_STATE_KEY)) {
            return new Response(
                '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8">'
                .'<title>Session perdue</title></head><body>'
                .'<h1>Session perdue pendant l\'authentification</h1>'
                .'<p>Le fournisseur a bien renvoyé un code d\'autorisation, mais la session'
                .' ne le retrouve pas : le cookie de session n\'a pas été conservé.</p>'
                .'<p><a href="'.$this->urlGenerator->generate('app_login').'">Réessayer</a></p>'
                .'</body></html>',
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'text/html; charset=utf-8']
            );
        }

        if ('OIDC' === $this->modeAuth) {
            return new RedirectResponse($this->oidcClient->createAuthorizationUrlSymfonySession($request->getSession()));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}
