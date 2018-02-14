<?php

namespace AppBundle\Event;

class EpisodeLogstateEventListener {

    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function onEpisodeStart($event)
    {
        $uniqID = $event->getLogstate()->getIdentifier();
        $episode = $this->em->getRepository('AppBundle:Episode')->findOneBy(['uniqID' => $uniqID]);
        if ($episode) { //episode found, update viewcount
            $episode->setViews($episode->getViews() +1);
            $this->em->persist($episode);
            $this->em->flush();
        }
    }

    public function onPlaylistStart($event)
    {
        $uniqID = $event->getLogstate()->getIdentifier();
        $playlist = $this->em->getRepository('AppBundle:Playlist')->findOneBy(['uniqID' => $uniqID]);
        if ($playlist) {
            $playlist->setViews($playlist->getViews() +1);
            $this->em->persist($playlist);
            $this->em->flush();
        }
    }
}
