<?php

namespace AppBundle\Event;

use Oktolab\MediaBundle\Event\DeleteEpisodeEvent;

class DeleteEpisodeEventListener {

    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function onEpisodeDelete(DeleteEpisodeEvent $event)
    {
        $comments = $this->em->getRepository('AppBundle:Comment')->findBy(['referer' => $event->getEpisode()->getUniqID()]);
        foreach ($comments as $comment) {
            $this->em->remove($comment);
        }
        $this->em->flush();
    }
}

?>
