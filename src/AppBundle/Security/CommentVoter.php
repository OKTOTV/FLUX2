<?php

namespace AppBundle\Security;

use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    const EDIT = 'user_edit';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Comment) {
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

        switch($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    // allow user to post in the backend
    private function canEdit(Comment $comment, User $user)
    {
        return $comment->getUser() === $user && $comment->getCreatedAt() > new \DateTime('-7 days');
    }
}
