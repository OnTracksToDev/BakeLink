<?php

namespace App\Security\Voter;

use App\Entity\RequestOrder;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RequestOrderVoter extends Voter
{
    public const EDIT = 'REQUEST_ORDER_EDIT';
    public const VIEW = 'REQUEST_ORDER_VIEW';
    public const DELETE = 'REQUEST_ORDER_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof RequestOrder;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        $requestOrder = $subject;

        if ($this->isAdmin($user)) {
            return true;
        }

        switch ($attribute) {
            case self::EDIT:
                return $user === $requestOrder->getPastryChef();
                break;

            case self::VIEW:
                return $user === $requestOrder->getClient() || $user === $requestOrder->getPastryChef();
                break;

            case self::DELETE:
                return $user === $requestOrder->getClient() || $user === $requestOrder->getPastryChef();
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function isAdmin(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}
