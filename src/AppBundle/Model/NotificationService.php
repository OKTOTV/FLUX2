<?php

namespace AppBundle\Model;

use AppBundle\Entity\Notification;

class NotificationService
{
    private $em;
    private $mailer;
    private $livestream_template;
    private $new_episode_template;
    private $new_post_template;
    private $translator;

    public function __construct($em, $mailer, $translator, $livestream_template, $new_post_template, $new_episode_template)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->livestream_template = $livestream_template;
        $this->new_post_template = $new_post_template;
        $this->new_episode_template = $new_episode_template;
    }

    public function createNotificationsForSeries($series, $type, $email = false )
    {
        $users = [];
        switch ($type) {
            case Notification::LIVESTREAM:
                $users = $this->em->getRepository('AppBundle:User')
                    ->findAboUsersWithNotificationForLivestream($series, $email);
                break;
            case Notification::NEW_EPISODE:
                $users = $this->em->getRepository('AppBundle:User')
                    ->findAboUsersWithNotificationForNewEpisodes($series, $email);
                break;
            case Notification::NEW_POST:
                $users = $this->em->getRepository('AppBundle:User')
                    ->findAboUsersWithNotificationNewPost($series, $email);
                break;
        }

        $batchSize = 0;
        foreach($users as $user) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setSeries($series);
            $notification->setType($type);

            $this->em->persist($notification);
            if (($batchSize % 20) == 0) {
                $batchSize = 0;
                $this->em->flush();
                $this->em->clear();
            }
        }

        if ($email) {
            // TODO: email service send emails
            switch ($type) {
                case Notification::LIVESTREAM:
                    $subject = $this->translator->trans('oktothek_mail_notification_livestream_subject');
                    foreach($users as $user) {
                        $this->mailer->sendMail($user->getEmail(), $this->livestream_template, [], $subject);
                    }
                    break;
                case Notification::NEW_EPISODE:
                    $subject = $this->translator->trans('oktothek_mail_notification_new_episode_subject');
                    foreach($users as $user) {
                        $this->mailer->sendMail($user->getEmail(), $this->new_episode_template, [], $subject);
                    }
                    break;
                case Notification::NEW_POST:
                    $subject = $this->translator->trans('oktothek_mail_notification_new_post_subject');
                    foreach($users as $user) {
                        $this->mailer->sendMail($user->getEmail(), $this->new_post_template, [], $subject);
                    }
                    break;
            }
        }
    }
}
