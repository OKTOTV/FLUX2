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
        $array = $this->get('bprs_analytics')->trackInfo($request, $request->get('values'));
        if ($array) {
            $this->get('bprs_analytics')->logState($array);
            return new Response();
        }
        return new Response(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @Route("/read_log.{_format}", name="bprs_analytics_for_values", defaults={"_format": "html"})
     * @Template()
     */
    public function logstateForValuesAction(Request $request)
    {
        $values = $request->get('values');
        // die(var_dump($values));
        $logstates = $this->get('bprs_analytics')->getLogstatesInTimeForValues($values);
        $logstates = $this->get('bprs_analytics')->groupLogstatesByTimeInterval($logstates, '+10 minutes');
        return ['logstates' => $logstates];
    }
}
