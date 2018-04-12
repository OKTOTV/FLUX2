<?php

namespace AppBundle\Security;

use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    const EDIT = 'user_edit';
    const DELETE = 'user_delete_comment';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
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
                break;
            case self::DELETE:
                return $this->canDelete($subject, $user);
                break;
        }

        throw new \LogicException('This code should not be reached!');
    }

    // allow user to post in the backend
    private function canEdit(Comment $comment, User $user)
    {
        return $comment->getUser()->getId() === $user->getId() && $comment->getCreatedAt() > new \DateTime('-12 hours');
    }

    // allow user to delete his post anytime
    private function canDelete(Comment $comment, User $user)
    {
        // die(var_dump($comment->getUser()->getId()));
        return $comment->getUser()->getId() === $user->getId();
    }
}
