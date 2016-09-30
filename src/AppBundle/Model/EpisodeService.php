<?php

namespace AppBundle\Model;

use AppBundle\Entity\Episode;

class EpisodeService {

    private $notificator;
    private $logger;
    private $em;

    public function __construct($logger, $notificator, $em)
    {
        $this->notificator = $notificator;
        $this->logger = $logger;
        $this->em = $em;
    }

    public function publishEpisode(Episode $episode)
    {
        if ($episode->getTechnicalStatus() >= Episode::STATE_READY) {
            // $this->logger->info();
            $episode->setIsActive(true);
            $episode->setOnlineStart(new \Datetime());
            $this->em->persist($episode);
            $this->em->flush();
            $this->notificator->createNewEpisodeNotifications($episode);
            return true;
        }
        // can't publish episode if not ready
        return false;
    }

}

 ?>
