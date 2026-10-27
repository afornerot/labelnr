<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Crée et rafraîchit l'utilisateur local à partir des claims d'un fournisseur
 * d'identité fédéré (OIDC).
 *
 * @implements UserProviderInterface<User>
 */
class IdentityUserProvider implements UserProviderInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
        #[Autowire(param: 'oidcMailAttribute')]
        private string $oidcMailAttribute,
    ) {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->findOneBy(['username' => $identifier]);

        if (!$user) {
            throw new UserNotFoundException(sprintf('User with username "%s" not found.', $identifier));
        }

        return $user;
    }

    /**
     * @param array<string, mixed> $claims
     */
    public function loadUserByIdentifierAndClaims(string $identifier, array $claims): UserInterface
    {
        $email = $claims[$this->oidcMailAttribute] ?? '';

        if (!is_string($email) || '' === $email) {
            throw new UserNotFoundException(sprintf('Attribut email "%s" manquant pour l\'utilisateur "%s". Vérifier la configuration du provider d\'identité.', $this->oidcMailAttribute, $identifier));
        }

        $user = $this->userRepository->findOneBy(['username' => $identifier]);
        $isNew = null === $user;

        if (!$isNew && $user->getEmail() !== $email) {
            $this->assertEmailIsFree($email, $user->getId());
        }

        if ($isNew) {
            // L'email doit être libre : sans ce garde-fou, la contrainte
            // d'unicité se traduit par une 500 Doctrine au premier login.
            $this->assertEmailIsFree($email, null);

            $user = new User();
            $user->setUsername($identifier);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, Uuid::uuid4()->toString())
            );
            $user->setRoles(['ROLE_USER']);
        }

        $dirty = $user->getEmail() !== $email;
        $user->setEmail($email);

        if ($isNew) {
            $this->em->persist($user);
            $this->em->flush();
        } elseif ($dirty) {
            $this->em->flush();
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $refreshed = $this->userRepository->find($user->getId());

        if (!$refreshed) {
            throw new UserNotFoundException(sprintf('User with id "%s" not found.', $user->getId()));
        }

        return $refreshed;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    private function assertEmailIsFree(string $email, ?int $currentUserId): void
    {
        $existing = $this->userRepository->findOneBy(['email' => $email]);

        if (null !== $existing && $existing->getId() !== $currentUserId) {
            throw new AuthenticationException(sprintf('Authentification refusée : l\'email "%s" est déjà utilisé par un autre compte.', $email));
        }
    }
}
