<?php

namespace AppBundle\Security;

use AppBundle\Entity\Abonnement;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SubscriptionVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Abonnement) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Abonnement object, thanks to supports
        /** @var abonnement $abonnement */
        $abonnement = $subject;

        switch($attribute) {
            case self::VIEW:
                return $this->canView($abonnement, $user);
            case self::EDIT:
                return $this->canEdit($abonnement, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Abonnement $abonnement, User $user)
    {
        return $abonnement->getUser() === $user;
    }

    private function canEdit(Abonnement $abonnement, User $user)
    {
        return $abonnement->getUser() === $user;
    }
}
