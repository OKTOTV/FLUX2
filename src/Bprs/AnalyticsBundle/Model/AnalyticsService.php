<?php

namespace Bprs\AnalyticsBundle\Model;

use Bprs\AnalyticsBundle\Entity\Logstate;
use Bprs\AnalyticsBundle\Entity\Info;
use Symfony\Component\HttpFoundation\Request;


class AnalyticsService {

    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * tracks info for given request and additional optional informations (for example: ["uniqID" => 123456, "player_second" => 20])
     * from, to and ignore_abuse let you set a timeframe in wich a request won't be logged again (refresh the page to generate clicks) or ignore them.
     */
    public function trackInfo(Request $request, $array = false, $from = false, $to = false, $ignore_abuse = false)
    {
        $informations = $this->requestToArray($request, $array);
        $this->logState($informations, $from, $to, $ignore_abuse);
    }

    public function getLogstatesInTimeForValues($values, $from = false, $to = false)
    {
        return $this->em->getRepository('BprsAnalyticsBundle:Logstate')->getLogstatesInTimeForValues($values, $from, $to);
    }

    /**
     * groups timestamp ordered logstates in a given timeinterval.
     * @return an array with datetimes => $logstates;
     */
    public function groupLogstatesByTimeInterval($logstates, $interval = "+1 hour")
    {
        $result = [];
        if (count($logstates)) {
            $day = $logstates[0]->getTimestamp();
            $day->modify($interval);
            foreach ($logstates as $logstate) {
                if ($logstate->getTimestamp() < $day) { // timestamp is in timerange
                    $result[$day->format('H:i d.m.Y')][] = $logstate;
                } else { // timestamp is not in timerange. move on to next timerange
                    $loop = true;
                    while ($loop) {
                        $day->modify($interval);
                        if ($logstate->getTimestamp() < $day) {
                            $result[$day->format('H:i d.m.Y')][] = $logstate;
                            $loop = false;
                        } else {
                            $result[$day->format('H:i d.m.Y')][] = null;
                            $loop = false;
                        }
                    }
                }
            }
            return $result;
        }
        return $result;
    }

    /**
     * writes a new log line.
     * @param $array see Logstate Entity constants for available infos to log
     */
    private function logState($array, $from = false, $to = false, $ignore_abuse = false)
    {
        if (!$this->checkForRefreshes($array[Logstate::BPRS_AN_CLIENTIP], $array[Logstate::BPRS_AN_URL], $from, $to) || $ignore_abuse) {
            $logstate = new Logstate();
            $logstate->setReferer($array[Logstate::BPRS_AN_REFERER]);
            $logstate->setUrl($array[Logstate::BPRS_AN_URL]);
            $logstate->setUserAgent($array[Logstate::BPRS_AN_USERAGENT]);
            $logstate->setClientIp($array[Logstate::BPRS_AN_CLIENTIP]);

            foreach ($array[Logstate::BPRS_AN_VALUES] as $key => $value) {
                $info = new Info($key, $value);
                $logstate->addValue($info);
                $this->em->persist($info);
            }

            $this->em->persist($logstate);
            $this->em->flush();
        }
    }

    private function requestToArray(Request $request, $additionalInfo = false)
    {
        $array = [];
        $array[Logstate::BPRS_AN_REFERER] = $request->headers->get('referer');
        $array[Logstate::BPRS_AN_URL] = $request->getRequestUri();
        $array[Logstate::BPRS_AN_USERAGENT] = $request->headers->get('user-agent');
        $array[Logstate::BPRS_AN_CLIENTIP] = $request->getClientIp();

        if ($additionalInfo) {
            $array[Logstate::BPRS_AN_VALUES] = $additionalInfo;
        }

        return $array;
    }

    /**
     * returns true of false if there's already a log line for given informations
     * true if logstates have been found
     * false if you can write a logstate
     */
    private function checkForRefreshes($client_ip, $url, $from = false, $to = false)
    {
        $refreshes = $this->em->getRepository('BprsAnalyticsBundle:Logstate')->getNumberOfRefreshes($client_ip, $url, $from, $to);
        if ($refreshes > 0) {
            return true;
        }

        return false;
    }
}

 ?>
