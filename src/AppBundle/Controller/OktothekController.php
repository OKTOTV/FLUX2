<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Episode;
use AppBundle\Entity\Series;
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
     * @Template()
     */
    public function showEpisodeAction(Episode $episode)
    {
        $episodes = $this->getDoctrine()->getRepository('AppBundle:Episode')->findNewerEpisodes($episode, 3);
        return array('episode' => $episode, 'related' => $episodes);
    }

    /**
     * @Route("episode/{uniqID}/embed", name="oktothek_embed_episode")
     * @Template()
     */
    public function embedEpisodeAction(Episode $episode)
    {
        return ['episode' => $episode];
    }


    /**
     * @Route("/series/{uniqID}", name="oktothek_show_series")
     * @Template()
     */
    public function showSeriesAction(Series $series)
    {
        return array('series' => $series);
    }

}
?>
