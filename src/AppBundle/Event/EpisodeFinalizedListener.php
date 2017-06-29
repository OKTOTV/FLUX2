<?php

namespace AppBundle\Event;

/**
 * send notification to episode series' channelmanagers that want notifications for finalized episodes (boolean)
 */
class EpisodeFinalizedListener {

    private $notification_service;

    public function __construct($not_ser)
    {
        $this->notification_service = $not_ser;
    }

    public function onFinalizedEpisode($episodeEvent)
    {
        $channelManagers = $episodeEvent->getEpisode()->getSeries()->getChannelmanagers();
        foreach ($channelManagers as $channelManager) {
            if ($channelManager->getNotificationForFinalizedEpisodes() == 1) {
                $this->notification_service->addNewNotification(
                    $channelManager,
                    "okto_media.notification_finalized_episode",
                    ['%episode%' => $episode->getUniqID()]
                );
            }

            if ($channelManager->getNotificationForFinalizedEpisodes() == 2) {
                $this->notification_service->addNewNotification(
                    $channelManager,
                    "okto_media.notification_finalized_episode",
                    ['%episode%' => $episode->getUniqID()],
                    "MAILTEMPLATE::finalizedEpisode.html.twig",
                    "okto_media.notification_finalized_episode_subject"
                );
            }
        }
    }
}

 ?>
