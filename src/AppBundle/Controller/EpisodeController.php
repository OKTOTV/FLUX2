<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Episode;

/**
 * Episode controller.
 *
 * @Route("/oktothek")
 */
class EpisodeController extends Controller
{

    /**
     * @Route("/episode/{uniqID}.{_format}", name="oktothek_show_episode", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, Episode $episode)
    {
        if (!$episode->canBeOnline()) {
            throw $this->createNotFoundException();
        }

        $this->get('bprs_analytics')->trackInfo($request, $episode->getUniqID());

        $em = $this->getDoctrine()->getManager();
        $comments = $this->get('knp_paginator')->paginate(
            $em->getRepository('AppBundle:EpisodeComment')->findComments(0, true),
            $request->query->get('page', 1),
            $request->query->get('results', 10)
        );

        $next = $this->getDoctrine()->getRepository('AppBundle:Episode')->findNextEpisode($episode);
        return [
            'episode' => $episode,
            'next' => $next,
            'start' => $request->query->get('start', false),
            'comments' => $comments
        ];
    }

    /**
     * @Route("/episode_player/{uniqID}.{_format}", name="oktothek_show_episode_player", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function playerAction(Request $request, Episode $episode)
    {
        if (!$episode->canBeOnline()) {
            throw $this->createNotFoundException();
        }
        $this->get('bprs_analytics')->trackInfo($request, $episode->getUniqID());
        $next = $this->getDoctrine()->getRepository('AppBundle:Episode')->findNextEpisode($episode);
        return ['episode' => $episode, 'next' => $next];
    }

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
        $number = $request->query->get('results', 12);
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
        $number = $request->query->get('results', 12);
        $number = ($number > 20) ? 20 : $number;
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Episode')->findNewestEpisodes(0, true);
        $paginator = $this->get('knp_paginator');
        $episodes = $paginator->paginate($query, $page, $number);
        return ['episodes' => $episodes];
    }
}
