<?php

namespace Bprs\AnalyticsBundle\Model;

use Bprs\AnalyticsBundle\Entity\Logstate;
use Bprs\AnalyticsBundle\Entity\Info;
use Symfony\Component\HttpFoundation\Request;
use Bprs\AnalyticsBundle\Event\LogstateEvent;


class AnalyticsService {

    private $em;
    private $dispatcher;

    public function __construct($em, $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * tracks info for given request
     * from, to and ignore_abuse let you set a timeframe in wich a request
     * won't be logged again (refresh the page to generate clicks)
     * or ignore them.
     */
    public function trackInfo(Request $request, $identifier = null, $value = null, $from = '-5 minutes', $ignore_abuse = false)
    {
        $array = $this->requestToArray($request);
        if (!$this->checkForRefreshes($array[Logstate::BPRS_AN_CLIENTIP], $array[Logstate::BPRS_AN_URL], $from) || $ignore_abuse) {
            $logstate = new Logstate();
            $logstate->setReferer($array[Logstate::BPRS_AN_REFERER]);
            $logstate->setUrl($array[Logstate::BPRS_AN_URL]);
            $logstate->setUserAgent($array[Logstate::BPRS_AN_USERAGENT]);
            $logstate->setClientIp($array[Logstate::BPRS_AN_CLIENTIP]);
            $logstate->setIdentifier($identifier);
            $logstate->setValue($value);

            $this->em->persist($logstate);
            $this->em->flush();
            $event = new LogstateEvent($logstate);
            if ($value) {
                $this->dispatcher->dispatch($value, $event);
            }
            return true;
        }
        return false;
    }

    public function getLogstatesInTime($values, $from = '-2 weeks', $to = 'now')
    {
        return $this->em->getRepository('BprsAnalyticsBundle:Logstate')->getLogstatesInTime($values, $from, $to);
    }

    /**
     * groups timestamp ordered logstates in a given timeinterval.
     * @return an array with datetimes => $logstates;
     */
    public function groupLogstatesByTimeInterval($logstates, $interval = "+1 hour", $from)
    {
        $result = [];
        if (count($logstates)) {
            $from = new \DateTime($from);
            $slot = new \DateTime($from->format('Y-m-d 00:00:00'));
            $block = new \DateTime($from->format('Y-m-d 00:00:00'));
            $slot->modify($interval);
            foreach ($logstates as $logstate) {
                if ($logstate->getTimestamp() <= $slot) { // timestamp is in timerange
                    $result[$block->format('H:i:s d.m.Y')][] = $logstate;
                } else { // timestamp is not in timerange. move on to next timerange
                    $loop = true;
                    while ($loop) {
                        $block->modify($interval);
                        $slot->modify($interval);
                        if ($logstate->getTimestamp() <= $slot) {
                            $result[$block->format('H:i:s d.m.Y')][] = $logstate;
                            $loop = false;
                        } else {
                            $result[$block->format('H:i:s d.m.Y')] = null;
                        }
                    }
                }
            }
            return $result;
        }
        return $result;
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
    private function checkForRefreshes($client_ip, $url, $from = '-5 minutes', $to = 'now')
    {
        $refreshes = $this->em->getRepository('BprsAnalyticsBundle:Logstate')->getNumberOfRefreshes($client_ip, $url, $from, $to);
        if ($refreshes > 0) {
            return true;
        }

        return false;
    }
}

 ?>
