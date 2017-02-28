<?php

namespace AppBundle\Event;

use Bprs\AssetBundle\Event\CreateAssetEvent;

class AssetEventListener {

    private $security;

    public function __construct($security)
    {
        $this->security = $security;
    }

    public function onPrePersist(CreateAssetEvent $event)
    {
        $event->getAsset()->setOwner($this->security->getToken()->getUser());
    }
}

?>
