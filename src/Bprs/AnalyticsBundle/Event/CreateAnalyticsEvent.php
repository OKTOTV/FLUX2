<?php

namespace Bprs\AnalyticsBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class CreateAnalyticsEvent extends Event
{
    protected $informations;

    public function __construct($informations) {
        $this->informations = $informations;
    }

    public function getInformations() {
        return $this->informations;
    }
}
