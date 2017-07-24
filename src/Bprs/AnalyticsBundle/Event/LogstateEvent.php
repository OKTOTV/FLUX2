<?php

namespace Bprs\AnalyticsBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class LogstateEvent extends Event
{
    protected $logstate;

    public function __construct($logstate) {
        $this->logstate = $logstate;
    }

    public function getLogstate() {
        return $this->logstate;
    }
}
