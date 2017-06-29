<?php

namespace AppBundle\Event;

/**
 * creates and sends notifications for given events.
 */
class NotificationListener
{
    private $notificationService;
    private $livestream_template;
    private $new_episode_template;
    private $new_post_template;
    private $em;
    private $router;
    private $new_comment_onBlogpost_template;
    private $new_comment_onEpisode_template;

    public function __construct($notificationService, $em, $router, $livestream_template, $new_post_template, $new_episode_template, $new_comment_onBlogpost_template, $new_comment_onEpisode_template)
    {
        $this->notificationService = $notificationService;
        $this->em = $em;
        $this->livestream_template = $livestream_template;
        $this->new_post_template = $new_post_template;
        $this->new_episode_template = $new_episode_template;
        $this->router = $router;
        $this->new_comment_onBlogpost_template = $new_comment_onBlogpost_template;
        $this->new_comment_onEpisode_template = $new_comment_onEpisode_template;
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
                    $this->new_episode_template,
                    'oktothek.notification_message_episode',
                    [
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
                        $this->new_post_template,
                        'oktothek.notification_message_post',
                        [
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
                        $this->livestream_template,
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
                        $this->new_comment_onBlogpost_template,
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

    public function onCommentOnEpisode($episode)
    {
        $abonnements = $this->em->getRepository('AppBundle:Abonnement')->findAbonnementsForNewEpisodeComment($episode->getSeries());
        foreach ($abonnements as $abonnement) {
            if ($abonnement->getNewCommentOnEpisode()) {
                if ($abonnement->getSendMails()) {
                    $this->notificationService->addNewNotification(
                        $abonnement->getUser(),
                        $this->router->generate('oktothek_show_episode', ['uniqID' => $episode->getUniqID()]),
                        'oktothek.notification_message_episode_new_comment',
                        ['%series%' => $episode->getSeries()->getName()],
                        $this->new_comment_onEpisode_template,
                        'oktothek.notification_message_episode_new_comment',
                        [
                            'notification' => $abonnement,
                            'episode' => $episode
                        ]
                    );
                } else {
                    $this->notificationService->addNewNotification(
                        $abonnement->getUser(),
                        $this->router->generate('oktothek_show_episode', ['uniqID' => $episode->getUniqID()]),
                        'oktothek.notification_message_episode_new_comment',
                        ['%series%' => $episode->getSeries()->getName()]
                    );
                }
            }
        }
    }

    public function onFinalizedEpisode($event)
    {
        $episode = $event->getEpisode();
        $abonnements = $this->em->getRepository('AppBundle:Abonnement')->findBy(['series' => $episode->getSeries()->getId()]);
        foreach ($abonnements as $abonnement) {
            if ($abonnement->getEncodedEpisode()) {
                if ($abonnement->getSendMails()) {
                    $this->notificationService->addNewNotification(
                        $abonnement->getUser(),
                        $this->router->generate('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]),
                        'oktothek.notification_message_finalized_episode',
                        [],
                        $this->new_episode_template,
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
}
