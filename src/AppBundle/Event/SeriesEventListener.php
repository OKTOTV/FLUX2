<?php

namespace AppBundle\Event;

use Oktolab\MediaBundle\Event\DeleteSeriesEvent;

class SeriesEventListener {

    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function preSeriesDelete(DeleteSeriesEvent $event)
    {
        $assets = $this->em->getRepository('AppBundle:Asset')->findBy(['series' => $event->getSeries()->getId()]);
        foreach ($assets as $asset) {
            $this->em->remove($asset);
        }
        $this->em->flush();
    }
}

?>
