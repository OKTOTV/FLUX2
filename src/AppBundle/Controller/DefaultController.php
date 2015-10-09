<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function oktothekAction()
    {
        $em = $this->getDoctrine()->getManager();
        $episodes = $em->getRepository('AppBundle:Episode')->findBy(array(), array('createdAt' => 'ASC'), 4);
        $newest_episodes = $em->getRepository('AppBundle:Episode')->findNewestEpisodes(4);
        $newest_playlists = $em->getRepository('AppBundle:Playlist')->findNewestPlaylists(4);

        return array(
            'episodes' => $episodes,
            'newest_episodes' => $newest_episodes,
            'newest_playlists' => $newest_playlists
        );
    }

    /**
     * @Route("/tv", name="tv")
     * @Template
     */
    public function tvAction()
    {
        return array();
    }

    /**
     * @Route("/participate", name="participate")
     * @Template
     */
    public function participateAction()
    {
        return array();
    }

    /**
     * @Route("/news", name="news")
     * @Template
     */
    public function newsAction()
    {
        return array();
    }

    /**
    * @Route("/slider", name="slider")
    * @Template()
    */
    public function sliderAction()
    {
        $em = $this->getDoctrine()->getManager();
        $episodePins = $em->getRepository('AppBundle:EpisodePin')->findBy(array(), array('onlineAt' => 'ASC'), 5, 0);

        return array('slides' => $episodePins);
    }
}
