<?php

namespace AppBundle\Model;

use AppBundle\Entity\Notification;
use AppBundle\Entity\Episode;
use AppBundle\Entity\Series;
use AppBundle\Entity\Post;

/**
 * creates and sends all notifications in an application.
 * It also sends out emails according to the abonnement settings of an user.
 */
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

    public function createNewEpisodeNotifications(Episode $episode)
    {
        $notifications = [];
        $mails = [];
        foreach ($episode->getSeries()->getAbonnements() as $abonnement) {
            $notification = new Notification();
            $notification->setEpisode($episode);
            $notification->setType(Notification::NEW_EPISODE);
            $abonnement->getUser()->addNotification($notification);
            $notifications[] = $notification;
            if ($abonnement->getSendMails()) {
                $mails[] = $notification;
            }
        }
        $this->sendMails(Notification::NEW_EPISODE, $mails);
        $this->saveNotifications($notifications);
    }

    public function createNewPostNotifications(Post $post)
    {
        if ($post->getOnlineAt() <= new \Datetime() && $post->getSeries() != null && $post->isActive()) {
            $notifications = [];
            $mails = [];
            foreach ($post->getSeries()->getAbonnements() as $abonnement) {
                if ($abonnement->getNewPost()) {
                    $notification = new Notification();
                    $notification->setUser($abonnement->getUser());
                    $notification->setPost($post);
                    $notification->setType(Notification::NEW_POST);
                    $notifications[] = $notification;
                    if ($abonnement->getSendMails()) {
                        $mails[] = $notification;
                    }
                }
            }
            $this->sendMails(Notification::NEW_POST, $mails);
            $this->saveNotifications($notifications);
        }
    }

    public function createLivestreamNotifications(Series $series)
    {
        $notifications = [];
        $mails = [];
        foreach ($post->getSeries()->getAbonnements() as $abonnement) {
            if ($abonnement->getNewPost()) {
                $notification = new Notification();
                $notification->setUser($abonnement->getUser());
                $notification->setPost($post);
                $notification->setType(Notification::LIVESTREAM);
                $notifications[] = $notification;
                if ($abonnement->send_mails()) {
                    $mails[] = $notification;
                }
            }
        }
        $this->sendMails(Notification::LIVESTREAM, $mails);
        $this->saveNotifications($notifications);
    }

    private function saveNotifications(array $notifications)
    {
        $batchSize = 0;
        foreach($notifications as $notification) {
            $this->em->persist($notification);
            $batchSize++;
            if (($batchSize % 20) == 0) {
                $batchSize = 0;
                $this->em->flush();
                $this->em->clear();
            }
        }
        $this->em->flush();
        $this->em->clear();
    }

    private function sendMails($type, array $notifications)
    {
        switch ($type) {
            case Notification::LIVESTREAM:
                $subject = $this->translator->trans('oktothek_mail_notification_livestream_subject');
                foreach($notifications as $notification) {
                    $this->mailer->sendMail(
                        $notification->getUser()->getEmail(),
                        $this->livestream_template,
                        ['notification' => $notification],
                        $subject
                    );
                }
                break;
            case Notification::NEW_EPISODE:
                $subject = $this->translator->trans('oktothek_mail_notification_new_episode_subject');
                foreach($notifications as $notification) {
                    $this->mailer->sendMail(
                        $notification->getUser()->getEmail(),
                        $this->new_episode_template,
                        ['notification' => $notification],
                        $subject
                    );
                }
                break;
            case Notification::NEW_POST:
                $subject = $this->translator->trans('oktothek_mail_notification_new_post_subject');
                foreach($notifications as $notification) {
                    $this->mailer->sendMail(
                        $notification->getUser()->getEmail(),
                        $this->new_post_template,
                        ['notification' => $notification],
                        $subject
                    );
                }
                break;
        }
    }
}
