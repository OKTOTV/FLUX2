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
}
