<?php

namespace AppBundle\Event;
use AppBundle\Entity\Notification;

class NotificationListener
{
    private $logger;
    private $notificator;

    public function __construct($logger, $notificator)
    {
        $this->logger = $logger;
        $this->notificator = $notificator;
    }

    public function createNewEpisodeNotifications($event)
    {
        $episode = $event->getEpisode();
        $this->notificator->createNotificationsForSeries($episode->getSeries(), Notification::NEW_EPISODE);
    }

    public function createNewPostNotifications($event)
    {
        $series = $event->getEpisode();
        $this->notificator->createNotificationsForSeries($series, Notification::NEW_POST);
    }

    public function createLivestreamNotifications($event)
    {
        $series = $event->getSeries();
        $this->notificator->createNotificationsForSeries($series, Notification::LIVESTREAM);
    }
}
