<?php

namespace AppBundle\Event;

class UserEventListener {

    private $em;
    private $mailer;
    private $translator;

    public function __construct($em, $mailer, $translator)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    public function onPostPasswordReset($event)
    {
        $user = $event->getUser();
        if ($user->getGreeted() != true) { //first time password reset (registered fully)
            $user->setGreeted(true);
            $this->em->persist($user);
            $this->em->flush();
            $this->mailer->sendMail(
                $user->getEmail(),
                "AppBundle::Mail/welcome.html.twig",
                ['user' => $user],
                $this->translator->trans('oktothek_user_welcome_subject', ['%user%' => $user->getUsername()])
            );
        }
    }

    // remove everything a user has saved in the database
    public function onPreDeleteUser($event)
    {
        $user = $event->getUser();
        // delete all episode comments
        foreach ($user->getEpisodeComments() as $comment) {
            foreach ($comment->getChildren() as $child) {
                $this->em->remove($child);
            }
            $this->em->remove($comment);
        }

        // delete all post comments
        foreach ($user->getPostComments() as $comment) {
            foreach ($comment->getChildren() as $child) {
                $this->em->remove($child);
            }
            $this->em->remove($comment);
        }

        // delete all abonnements
        foreach ($user->getAbonnements() as $abonnement) {
            $this->em->remove($abonnement);
        }

        // delete all playlists
        foreach ($user->getPlaylists() as $playlist) {
            $this->em->remove($playlist);
        }
        $this->em->flush();
    }
}
