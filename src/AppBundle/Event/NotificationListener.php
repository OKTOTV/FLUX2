<?php

namespace AppBundle\Event;
use Symfony\Component\Routing\RouterInterface;

/**
 * creates and sends notifications for given events.
 */
class NotificationListener
{
    private $notificationService;
    private $em;
    private $router;
    private $mailerhost;

    public function __construct($notificationService, $em, $router, $mailerhost)
    {
        $this->notificationService = $notificationService;
        $this->em = $em;
        $this->router = $router;
        $this->mailerhost = $mailerhost;
    }

    public function onNewEpisode($episode)
    {
        $abonnements = $this->em->getRepository('AppBundle:Abonnement')->findAbonnementsForNewEpisode($episode->getSeries());
        foreach ($abonnements as $abonnement) {
            if ($abonnement->getSendMails()) {
                $this->notificationService->addNewNotification(
                    $abonnement->getUser(),
                    $this->router->generate('oktothek_show_episode', ['uniqID' => $episode->getUniqID()]),
                    'oktothek.notification_message_episode',
                    ['%series%' => $episode->getSeries()->getName()],
                    "AppBundle::Mail/new_episode.html.twig",
                    'oktothek.notification_message_episode',
                    [
                        'abonnement' => $abonnement,
                        'episode' => $episode,
                        'notification' => $abonnement
                    ]
                );
            } else {
                $this->notificationService->addNewNotification(
                    $abonnement->getUser(),
                    $this->router->generate('oktothek_show_episode', ['uniqID' => $episode->getUniqID()]),
                    'oktothek.notification_message_episode',
                    ['%series%' => $episode->getSeries()->getName()]
                );
            }
        }
    }

    public function onNewPost($post)
    {
        $abonnements = $this->em->getRepository('AppBundle:Abonnement')->findAbonnementsForNewPost($post->getSeries());
        foreach ($abonnements as $abonnement) {
            if ($abonnement->getNewPost()) {
                if ($abonnement->getSendMails()) {
                    $this->notificationService->addNewNotification(
                        $abonnement->getUser(),
                        $this->router->generate('oktothek_show_series_blogpost', ['slug' => $post->getSlug()]),
                        'oktothek.notification_message_post',
                        ['%series%' => $post->getSeries()->getName()],
                        "AppBundle::Mail/new_post.html.twig",
                        'oktothek.notification_message_post',
                        [
                            'abonnement' => $abonnement,
                            'notification' => $abonnement,
                            'post' => $post
                        ]
                    );
                } else {
                    $this->notificationService->addNewNotification(
                        $abonnement->getUser(),
                        $this->router->generate('oktothek_show_series_blogpost', ['slug' => $post->getSlug()]),
                        'oktothek.notification_message_post',
                        ['%series%' => $post->getSeries()->getName()]
                    );
                }
            }
        }
    }

    public function onLivestream($series)
    {
        $abonnements = $this->em->getRepository('AppBundle:Abonnement')->findAbonnementsForLivestream($series);
        foreach ($abonnements as $abonnement) {
            if ($abonnement->getLivestream()) {
                if ($abonnement->getSendMails()) {
                    $this->notificationService->addNewNotification(
                        $abonnement->getUser(),
                        $this->router->generate('tv'),
                        'oktothek.notification_message_livestream',
                        ['%series%' => $series->getName()],
                        "AppBundle::Mail/livestream.html.twig",
                        'oktothek.notification_message_livestream',
                        [
                            'notification' => $abonnement
                        ]
                    );
                } else {
                    $this->notificationService->addNewNotification(
                        $abonnement->getUser(),
                        $this->router->generate('tv'),
                        'oktothek.notification_message_livestream',
                        ['%series%' => $series->getName()]
                    );
                }
            }
        }
    }

    public function onCommentBlogpost($post)
    {
        $abonnements = $this->em->getRepository('AppBundle:Abonnement')->findAbonnementsForNewBlogpostComment($post->getSeries());
        foreach ($abonnements as $abonnement) {
            if ($abonnement->getNewCommentOnBlogPost()) {
                if ($abonnement->getSendMails()) {
                    $this->notificationService->addNewNotification(
                        $abonnement->getUser(),
                        $this->router->generate('oktothek_show_series_blogpost', ['slug' => $post->getSlug()]),
                        'oktothek.notification_message_post_new_comment',
                        [],
                        "AppBundle::Mail/commentBlogpost.html.twig",
                        'oktothek.notification_message_post_new_comment',
                        [
                            'notification' => $abonnement,
                            'post' => $post
                        ]
                    );
                } else {
                    $this->notificationService->addNewNotification(
                        $abonnement->getUser(),
                        $this->router->generate('oktothek_show_series_blogpost', ['slug' => $post->getSlug()]),
                        'oktothek.notification_message_post_new_comment',
                        []
                    );
                }
            }
        }
    }

    public function onCommentOnEpisode($comment)
    {
        $abonnements = $this->em->getRepository('AppBundle:Abonnement')->findAbonnementsForNewEpisodeComment($comment->getEpisode()->getSeries());
        foreach ($abonnements as $abonnement) {
            if ($abonnement->getNewCommentOnEpisode()) {
                if ($abonnement->getSendMails()) {
                    $this->notificationService->addNewNotification(
                        $abonnement->getUser(),
                        $this->router->generate('oktothek_show_episode', ['uniqID' => $comment->getEpisode()->getUniqID()]),
                        'oktothek.notification_message_episode_new_comment',
                        ['%series%' => $comment->getEpisode()->getSeries()->getName()],
                        "AppBundle::Mail/commentEpisode.html.twig",
                        'oktothek.notification_message_episode_new_comment',
                        [
                            'notification' => $abonnement,
                            'comment' => $comment
                        ]
                    );
                } else {
                    $this->notificationService->addNewNotification(
                        $abonnement->getUser(),
                        $this->router->generate('oktothek_show_episode', ['uniqID' => $comment->getEpisode()->getUniqID()]),
                        'oktothek.notification_message_episode_new_comment',
                        ['%series%' => $comment->getEpisode()->getSeries()->getName()]
                    );
                }
            }
        }
    }

    /**
     * will be triggered mainly through a background worker. swiftmailer memory spool won't trigger.
     * use file spooling and a cronjob
     */
    public function onFinalizedEpisode($event)
    {
        $episode = $this->em->getRepository('AppBundle:Episode')->findOneBy(['uniqID' => $event->getUniqID()]);
        $abonnements = $this->em->getRepository('AppBundle:Abonnement')->findAbonnementsForFinalizedEpisode($episode->getSeries());
        foreach ($abonnements as $abonnement) {
            if ($abonnement->getSendMails()) {
                $this->router->getContext()->setHost($this->mailerhost);
                $this->notificationService->addNewNotification(
                    $abonnement->getUser(),
                    $this->router->generate('oktolab_episode_show', ['uniqID' => $episode->getUniqID()], RouterInterface::ABSOLUTE_URL),
                    'oktothek.notification_message_finalized_episode',
                    ['%series%' => $episode->getSeries()->getName()],
                    "AppBundle::Mail/finalizedEpisode.html.twig",
                    'oktothek.notification_message_finalized_episode',
                    [
                        'notification' => $abonnement,
                        'episode' => $episode
                    ]
                );
            } else {
                $this->notificationService->addNewNotification(
                    $abonnement->getUser(),
                    $this->router->generate('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]),
                    'oktothek.notification_message_finalized_episode',
                    []
                );
            }
        }
    }
}
