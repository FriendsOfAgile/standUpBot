<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['GET_ITEM', 'EDIT', 'DELETE'])
            && $subject instanceof User;
    }

    /**
     * @param string $attribute
     * @param User $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'GET_ITEM':
            case 'EDIT':
                return $user->getIsAdmin() || ($user->getId() === $subject->getId());
                break;
            case 'DELETE':
                return $user->getIsAdmin() && $subject->getId() != $user->getId();
                break;
        }

        return false;
    }
}
