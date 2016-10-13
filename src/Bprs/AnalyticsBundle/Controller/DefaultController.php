<?php

namespace Bprs\AnalyticsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bprs\AnalyticsBundle\Entity\Logstate;

/**
 * @Route("/bprs_analytics")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/write_log", name="bprs_analytics_write_log")
     */
    public function writeLogstateAction(Request $request)
    {
        $this->get('bprs_analytics')->trackInfo($request, $request->get('identifier', null), $request->get('value', null));
        return new Response();
    }

    /**
     * @Route("/read_log.{_format}", name="bprs_analytics_for_values", defaults={"_format": "html"})
     * @Template()
     */
    // public function logstateForValuesAction(Request $request)
    // {
    //     $values = $request->get('values');
    //     $interval = $request->query->get('interval', '+1 day');
    //     $logstates = $this->get('bprs_analytics')->getLogstatesInTimeForValues($values, '-2 weeks');
    //     $logstates = $this->get('bprs_analytics')->groupLogstatesByTimeInterval($logstates, $interval);
    //     return ['logstates' => $logstates];
    // }
}
