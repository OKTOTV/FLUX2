<?php

namespace AppBundle\Model;

class ProducerService {

    private $analytics;

    public function __construct($analytics)
    {
        $this->analytics = $analytics;
    }

    public function getAnalyticsForEpisode($episode, $interval = '+1 day', $from = '-2 weeks', $to = 'now')
    {
        $logstates20 = $this->analytics->getLogstatesInTime(['identifier' => $episode->getUniqID(), 'value' => '20%'], $from, $to);
        $logstates20 = $this->analytics->groupLogstatesByTimeInterval($logstates20, $interval, $from);

        return ['20' => $logstates20];
    }

    public function getAnalyticsForSeries($series, $interval = '+1 day', $from = '-2 weeks', $to = 'now')
    {
        $logstates = $this->analytics->getLogstatesInTime(['identifier' => $series->getUniqID()], $from, $to);
        $logstates = $this->analytics->groupLogstatesByTimeInterval($logstates, $interval);
        return $logstates;
    }
}

 ?>
