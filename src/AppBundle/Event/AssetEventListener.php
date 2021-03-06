<?php

namespace AppBundle\Event;

use Bprs\AssetBundle\Event\CreateAssetEvent;

class AssetEventListener {

    private $security;
    private $em;

    public function __construct($security, $em)
    {
        $this->security = $security;
        $this->em = $em;
    }

    public function onPrePersist(CreateAssetEvent $event)
    {
        $event->getAsset()->setOwner($this->security->getToken()->getUser());
    }

    public function onPreDelete(DeleteAssetEvent $event)
    {
        # TODO: unlink coursepackages
        # remove slides
        $slide_repo = $this->em->getRepository("AppBundle::Slide");
        $slides = $slide_repo->findSlidesWithAsset($event->getAsset());
        foreach ($slides as $slide) {
            $this->em->remove($slide);
        }
        if ($slides) {
            $this->em->flush();
        }
    }
}

?>
