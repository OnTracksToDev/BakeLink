<?php

namespace App\Security\Voter;

use App\Entity\Client;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientVoter extends Voter
{
    public const EDIT = 'CLIENT_EDIT';
    public const DELETE = 'CLIENT_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Client;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        if (!$user instanceof UserInterface) {
            return false;
        }

        $client = $subject;

        if ($this->isAdmin($user)) {
            return true;
        }

        $isOwner = $user->getUserIdentifier() === $client->getUserIdentifier();

        switch ($attribute) {
            case self::EDIT:
            case self::DELETE:
                return $isOwner;
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function isAdmin(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}