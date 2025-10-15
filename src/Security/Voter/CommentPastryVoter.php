<?php

namespace App\Security\Voter;

use App\Entity\CommentPastry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentPastryVoter extends Voter
{
    public const EDIT = 'COMMENT_PASTRY_EDIT';
    public const DELETE = 'COMMENT_PASTRY_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof CommentPastry;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $commentPastry = $subject;

        if ($this->isAdmin($user)) {
            return true;
        }

        $isOwner = $user === $commentPastry->getClient();
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
