<?php

namespace App\Security\Voter;

use App\Entity\Pastry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PastryVoter extends Voter
{
    public const EDIT = 'PASTRY_EDIT';
    public const DELETE = 'PASTRY_DELETE';
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Pastry;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $pastry = $subject;

        if ($this->isAdmin($user)) {
            return true;
        }

        $isOwner = $user === $pastry->getPastryChef();

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
