<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MediaBundle\Entity\Episode;
use MediaBundle\Entity\Series;
use AppBundle\Entity\Playlist;

/**
 * Episode controller.
 *
 * @Route("/oktothek")
 */
class OktothekController extends Controller
{

    /**
     * @Route("/episode/{uniqID}.{_format}", name="oktothek_show_episode", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function showEpisodeAction(Episode $episode)
    {
        $episodes = $this->getDoctrine()->getRepository('MediaBundle:Episode')->findNewerEpisodes($episode, 3);
        return array('episode' => $episode, 'related' => $episodes);
    }

    /**
     * @Route("/episode/{uniqID}/embed", name="oktothek_embed_episode")
     * @Method("GET")
     * @Template()
     */
    public function embedEpisodeAction(Episode $episode)
    {
        return ['episode' => $episode];
    }

    /**
     * @Route("/playlist/{uniqID}/embed", name="oktothek_embed_playlist")
     * @Method({"GET"})
     * @Template()
     */
    public function embedPlaylistAction(Playlist $playlist)
    {
        return ['playlist' => $playlist];
    }


    /**
     * @Route("/series/{uniqID}", name="oktothek_show_series")
     * @Method("GET")
     * @Template()
     */
    public function showSeriesAction(Series $series)
    {
        $posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->findNewestPosts(5, $series);
        return ['series' => $series, 'teasers' => $posts];
    }

}
?>
