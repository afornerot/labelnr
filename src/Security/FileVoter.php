<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\TIRRepository;
use Bnine\FilesBundle\Security\AbstractFileVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class FileVoter extends AbstractFileVoter
{
    public function __construct(private TIRRepository $tirRepository)
    {
    }

    protected function canView(string $domain, $id, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return true;
    }

    protected function canEdit(string $domain, $id, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }

        switch ($domain) {
            case 'evidence':
                $evidence = $this->tirRepository->find($id);
                if ($evidence) {
                    return true;
                }
                break;
        }

        return false;
    }

    protected function canDelete(string $domain, $id, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }

        switch ($domain) {
            case 'evidence':
                $evidence = $this->tirRepository->find($id);
                if ($evidence) {
                    return true;
                }
                break;
        }

        return false;
    }
}
