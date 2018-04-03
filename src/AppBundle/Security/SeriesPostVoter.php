<?php

namespace AppBundle\Security;

use AppBundle\Entity\Series;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SeriesPostVoter extends Voter
{
    const VIEW = 'view_channel';
    const EDIT = 'edit_channel';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Series) {
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
        $series = $subject;

        switch($attribute) {
            case self::VIEW:
                return $this->canView($series, $user);
            case self::EDIT:
                return $this->canEdit($series, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    // allow user to view to backend
    private function canView(Series $series, User $user)
    {
        $users = $series->getUsers()->toArray();
        if (in_array($user, $users) || in_array('ROLE_USER', $user->getRoles())) {
            return true;
        }
        return false;
    }

    // allow user to post in the backend
    private function canEdit(Series $series, User $user)
    {
        $users = $series->getUsers()->toArray();
        return in_array($user, $users);
    }
}
