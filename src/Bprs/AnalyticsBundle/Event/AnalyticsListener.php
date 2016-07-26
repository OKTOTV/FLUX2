<?php

namespace Bprs\AnalyticsBundle\Event;

use Bprs\AnalyticsBundle\Event\CreateAnalyticsEvent;

class AnalyticsListener {

    private $analytics;
    private $logger;

    public function __construct($logger, $analytics)
    {
        $this->analytics = $analytics;
        $this->logger = $logger;
    }

    public function analyticsCreate(CreateAnalyticsEvent $event)
    {
        $logstate = $this->analytics->logState($event->getInformations());
        $this->logger->info('wrote new analytics info');
    }
}

?>
