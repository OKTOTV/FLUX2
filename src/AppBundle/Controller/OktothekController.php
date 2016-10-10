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
     * @Method("GET")
     * @Template()
     */
    public function showEpisodeAction(Request $request, Episode $episode)
    {
        $this->get('bprs_analytics')->trackInfo($request, ['uniqID' => $episode->getUniqID()]);
        $episodes = $this->getDoctrine()->getRepository('AppBundle:Episode')->findNewerEpisodes($episode, 3);
        $next = $this->getDoctrine()->getRepository('AppBundle:Episode')->findNextEpisode($episode);
        return ['episode' => $episode, 'related' => $episodes, 'next' => $next];
    }

    /**
     * @Route("/episode/{uniqID}/related.{_format}", name="oktothek_show_similar_episode", defaults={"_format": "json"})
     * @Method("GET")
     * @Template()
     */
    public function showRelatedEpisodesAction(Request $request, Episode $episode)
    {
        $next = $this->getDoctrine()->getRepository('AppBundle:Episode')->findNextEpisode($episode);
        $previous = $this->getDoctrine()->getRepository('AppBundle:Episode')->findPreviousEpisode($episode);
        $related = $this->get('oktothek_search')->searchRelatedEpisodes($episode, 5);
        $results = [];
        $results['episodes'] = $related;
        if ($next) {
            $results['next'] = $next;
        }

        if ($previous) {
            $results['prev'] = $previous;
        }
        return $results;
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
}
?>
