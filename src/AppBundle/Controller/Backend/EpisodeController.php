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
        $analytics = $this->get('bprs_analytics');
        $i = 0;
        while (($row = $iterableResult->next()) !== false) {
            $results[$row[$i]['uniqID']]['episode'] = $row[$i]['episodename'];
            $results[$row[$i]['uniqID']]['series'] = $row[$i]['seriesname'];
            $results[$row[$i]['uniqID']]['clicks'] = $row[$i]['views'];
            $results[$row[$i]['uniqID']]['20'] = $analytics->getCountOfLogstatesInTime(["value" => "20%", "identifier" => $row[$i]['uniqID']]);
            // $em->detach($row);
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
                        'Klicks'
                    ),
                    $delimiter
                );

            foreach ($results as $uniqID => $info) {
                fputcsv($handle,
                    [
                        $info["series"],
                        $info["episode"],
                        $uniqID,
                        $info["clicks"]
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
