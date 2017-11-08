<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    public function embedEpisodeAction(Request $request, Episode $episode)
    {
        return [
            'episode' => $episode,
            'start' => $request->query->get('start', false),
            'withtitle' => filter_var($request->query->get('title', true), FILTER_VALIDATE_BOOLEAN)
        ];
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
