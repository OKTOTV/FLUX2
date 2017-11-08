<?php

namespace AppBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Episode;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Series backend controller.
 *
 * @Route("/backend/oktothek")
 * @Security("has_role('ROLE_USER')")
 */
class EpisodeController extends Controller
{
    /**
     * @Route("/episode/publish/{uniqID}", name="oktothek_backend_publish_episode")
     * @Template()
     */
    public function publishAction(Episode $episode)
    {
        $episode_service = $this->get('oktothek_episode');
        if ($episode_service->publishEpisode($episode)) {
            $this->get('session')->getFlashBag()->add('success', 'oktothek.success_publish_episode');
        } else {
            $this->get('session')->getFlashBag()->add('info', 'oktothek.info_publish_episode');
        }
        return $this->redirect($this->generateUrl('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]));
    }

    // /**
    //  * @Route("/episode/statistic/{uniqID}", name="oktothek_backend_statistic_episode")
    //  * @Template()
    //  */
    // public function statisticAction(Request $request, Episode $episode)
    // {
    //     # code...
    // }

    /**
     * quick and dirty way to export statistics for all episodes
     * @Route("/episode/statistic/export", name="oktothek_backend_export_episode_statistics")
     * @Template()
     */
    public function exportClicksInTimerangeAction(Request $request)
    {
        $results = [];
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("SELECT e.name as episodename, e.views, e.uniqID, s.name as seriesname FROM AppBundle:Episode e LEFT JOIN e.series s");
        $iterableResult = $q->iterate();
        $logstates = $this->get('bprs_analytics')->getLogstatesInTime(['value' => '20%']);
        $logstates40 = $this->get('bprs_analytics')->getLogstatesInTime(['value' => '40%']);
        $logstates60 = $this->get('bprs_analytics')->getLogstatesInTime(['value' => '60%']);
        $logstates80 = $this->get('bprs_analytics')->getLogstatesInTime(['value' => '80%']);
        $logstatesEnd = $this->get('bprs_analytics')->getLogstatesInTime(['value' => 'end']);
        $i = 0;
        while (($row = $iterableResult->next()) !== false) {
            $results[$row[$i]['uniqID']]['episode'] = $row[$i]['episodename'];
            $results[$row[$i]['uniqID']]['series'] = $row[$i]['seriesname'];
            $results[$row[$i]['uniqID']]['clicks'] = $row[$i]['views'];
            $twenty_percent = 0;
            $fourty_percent = 0;
            $sixty_percent = 0;
            $eighty_percent = 0;
            $end = 0;
            foreach ($logstates as $logstate) {
                if ($logstate->getIdentifier() == $row[$i]['uniqID']) {
                    $twenty_percent++;
                    unset($logstate);
                }
            }
            foreach ($logstates40 as $logstate) {
                if ($logstate->getIdentifier() == $row[$i]['uniqID']) {
                    $fourty_percent++;
                    unset($logstate);
                }
            }
            foreach ($logstates60 as $logstate) {
                if ($logstate->getIdentifier() == $row[$i]['uniqID']) {
                    $sixty_percent++;
                    unset($logstate);
                }
            }
            foreach ($logstates80 as $logstate) {
                if ($logstate->getIdentifier() == $row[$i]['uniqID']) {
                    $eighty_percent++;
                    unset($logstate);
                }
            }
            foreach ($logstatesEnd as $logstate) {
                if ($logstate->getIdentifier() == $row[$i]['uniqID']) {
                    $end++;
                    unset($logstate);
                }
            }
            $results[$row[$i]['uniqID']]['20'] = $twenty_percent;
            $results[$row[$i]['uniqID']]['40'] = $fourty_percent;
            $results[$row[$i]['uniqID']]['60'] = $sixty_percent;
            $results[$row[$i]['uniqID']]['80'] = $eighty_percent;
            $results[$row[$i]['uniqID']]['end'] = $end;
            $em->clear();
            $i++;
        }

        $delimiter = ';';
        $response = new StreamedResponse(function() use($results, $delimiter) {
            $handle = fopen('php://output', 'r+');
                fputcsv($handle,
                    array(
                        'Sendereihe',
                        'Folge',
                        'UniqID',
                        'Klicks',
                        '20%',
                        '40%',
                        '60%',
                        '80%',
                        '100%'
                    ),
                    $delimiter
                );

            foreach ($results as $uniqID => $info) {
                fputcsv($handle,
                    [
                        $info["series"],
                        $info["episode"],
                        $uniqID,
                        $info["clicks"],
                        $info["20"],
                        $info["40"],
                        $info["60"],
                        $info["80"],
                        $info["end"]
                    ],
                    $delimiter
                );
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition','attachment; filename="episode_statistics.csv"');

        return $response;
    }
}
