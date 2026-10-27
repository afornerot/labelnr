<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class DynamicAuthenticator extends AbstractAuthenticator
{
    private string $modeAuth;
    private UserRepository $userRepository;
    private CasUserProvider $casUserProvider;
    private ParameterBagInterface $parameterBag;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(string $modeAuth, UserRepository $userRepository, CasUserProvider $casUserProvider, ParameterBagInterface $parameterBag, UrlGeneratorInterface $urlGenerator)
    {
        $this->modeAuth = $modeAuth;
        $this->userRepository = $userRepository;
        $this->casUserProvider = $casUserProvider;
        $this->parameterBag = $parameterBag;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        if ($request->getSession()->get('_security_main')) {
            return false;
        }

        if ($request->getSession()->has('_share_token')) {
            return false;
        }

        if ($request->query->has('appSecret') && 'bninefiles_files_download' === $request->attributes->get('_route')) {
            return true;
        }

        // En mode OIDC, l'authentification est portée par OidcAuthenticator et
        // AuthGuardEntryPoint : ce garde ne doit pas s'en mêler.
        if ('OIDC' === $this->modeAuth) {
            return false;
        }

        $currentPath = $request->getPathInfo();
        if (in_array($currentPath, ['/login', '/logout', '/rest/dossier', '/rest/weekly', '/api/print', '/share/'])) {
            return false;
        }

        return true;
    }

    public function authenticate(Request $request): Passport
    {
        if ($request->query->has('appSecret') && 'bninefiles_files_download' === $request->attributes->get('_route')) {
            $providedSecret = $request->query->get('appSecret');
            $appSecret = $this->parameterBag->get('appSecret');

            if ($providedSecret === $appSecret) {
                $apiUser = new InMemoryUser('api_user', null, ['ROLE_API_DOWNLOAD']);

                return new SelfValidatingPassport(new UserBadge($apiUser->getUserIdentifier(), fn () => $apiUser));
            } else {
                throw new AuthenticationException('Invalid appSecret for file download.');
            }
        }

        switch ($this->modeAuth) {
            case 'SQL':
                return $this->authenticateWithSql($request);

            case 'CAS':
                return $this->authenticateWithCas($request);
            default:
                throw new \InvalidArgumentException('Invalid authentication method');
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw $exception;
    }

    private function authenticateWithSql(Request $request): Passport
    {
        $username = $request->request->get('_username', '');
        $password = $request->request->get('_password', '');

        if (!$username || !$password) {
            throw new AuthenticationException('Username and password are required.');
        }

        // Charger l'utilisateur via Doctrine
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            throw new AuthenticationException('User not found.');
        }

        return new Passport(
            new UserBadge($username, function ($userIdentifier) {
                return $this->userRepository->findOneBy(['username' => $userIdentifier]);
            }),
            new PasswordCredentials($password)
        );
    }

    private function authenticateWithCas(Request $request): Passport
    {
        // Récupérer l'hôte d'origine derrière le reverse proxy
        $host = $request->headers->get('host');
        $scheme = $request->headers->get('X-Forwarded-Proto') ?? $request->getScheme();

        // Construire l'URL
        $url = $scheme.'://'.$host;

        // \phpCAS::setDebug('/tmp/logcas.log');
        \phpCAS::client(
            CAS_VERSION_2_0,
            $this->parameterBag->get('casHost'),
            (int) $this->parameterBag->get('casPort'),
            $this->parameterBag->get('casPath'),
            $url,
            false);

        \phpCAS::setNoCasServerValidation();
        \phpCAS::forceAuthentication();

        $username = \phpCAS::getUser();
        $attributes = \phpCAS::getAttributes();

        if (!$username) {
            throw new AuthenticationException('CAS authentication failed.');
        }

        $userBadge = new UserBadge($username, function ($userIdentifier) use ($attributes) {
            return $this->casUserProvider->loadUserByIdentifierAndCASAttributes($userIdentifier, $attributes);
        });

        return new SelfValidatingPassport($userBadge);
    }
}
