<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Series;

/**
 * Episode controller.
 *
 * @Route("/oktothek")
 */
class EpisodeController extends Controller
{
    /**
     * @Route(
     *    "/best_episodes.{_format}",
     *    name="oktothek_best_episodes",
     *    defaults={"_format": "html"}
     * )
     * @Template()
     */
    public function bestEpisodesAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $number = $request->query->get('results', 10);
        $number = ($number > 20) ? 20 : $number;
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Episode')->findBestEpisodes(0, true);
        $paginator = $this->get('knp_paginator');
        $episodes = $paginator->paginate($query, $page, $number);
        return ['episodes' => $episodes];
    }

    /**
     * @Route(
     *    "/newest_episodes.{_format}",
     *    name="oktothek_newest_episodes",
     *    defaults={"_format": "html"}
     * )
     * @Template()
     */
    public function newestEpisodesAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $number = $request->query->get('results', 10);
        $number = ($number > 20) ? 20 : $number;
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Episode')->findNewestEpisodes(0, true);
        $paginator = $this->get('knp_paginator');
        $episodes = $paginator->paginate($query, $page, $number);
        return ['episodes' => $episodes];
    }
}
