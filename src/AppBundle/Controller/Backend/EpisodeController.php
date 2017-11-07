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
        $episodes = $this->get('oktolab_media')->getEpisodeRepository()->findAll();
        $analytics = $this->get('bprs_analytics');
        foreach($episodes as $episode) {
            $results[$episode->getUniqID()]['start'] = count($analytics->getLogstatesInTime(['identifier' => $episode->getUniqID(), 'value' => 'start'], $request->query->get('starttime'), $request->query->get('endtime')));
            $results[$episode->getUniqID()]['20'] = count($analytics->getLogstatesInTime(['identifier' => $episode->getUniqID(), 'value' => '20%'], $request->query->get('starttime'), $request->query->get('endtime')));
            $results[$episode->getUniqID()]['40'] = count($analytics->getLogstatesInTime(['identifier' => $episode->getUniqID(), 'value' => '40%'], $request->query->get('starttime'), $request->query->get('endtime')));
            $results[$episode->getUniqID()]['60'] = count($analytics->getLogstatesInTime(['identifier' => $episode->getUniqID(), 'value' => '60%'], $request->query->get('starttime'), $request->query->get('endtime')));
            $results[$episode->getUniqID()]['80'] = count($analytics->getLogstatesInTime(['identifier' => $episode->getUniqID(), 'value' => '80%'], $request->query->get('starttime'), $request->query->get('endtime')));
            $results[$episode->getUniqID()]['end'] = count($analytics->getLogstatesInTime(['identifier' => $episode->getUniqID(), 'value' => 'end'], $request->query->get('starttime'), $request->query->get('endtime')));
            $results[$episode->getUniqID()]['episode'] = $episode->getName();
            $results[$episode->getUniqID()]['series'] = $episode->getSeries()->getName();
        }

        $delimiter = ';';
        $response = new StreamedResponse(function() use($results, $delimiter) {
            $handle = fopen('php://output', 'r+');
                fputcsv($handle,
                    array(
                        'Sendereihe',
                        'Folge',
                        'UniqID',
                        'Klicks für Start',
                        '20% gesehen',
                        '40% gesehen',
                        '60% gesehen',
                        '80% gesehen',
                        'Bis zum Schluss gesehen (100%)',
                    ),
                    $delimiter
                );

            foreach ($results as $uniqID => $info) {
                fputcsv($handle,
                    [
                        $info["series"],
                        $info["episode"],
                        $uniqID,
                        $info["start"],
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
