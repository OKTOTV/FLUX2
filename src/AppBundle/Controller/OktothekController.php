<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MediaBundle\Entity\Episode;
use MediaBundle\Entity\Series;
use MediaBundle\Entity\Playlist;

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
        $episodes = $this->getDoctrine()->getRepository('MediaBundle:Episode')->findNewerEpisodes($episode, 3);
        $next = $this->getDoctrine()->getRepository('MediaBundle:Episode')->findNextEpisode($episode);
        return ['episode' => $episode, 'related' => $episodes, 'next' => $next];
    }

    /**
     * @Route("/episode/{uniqID}/related.{_format}", name="oktothek_show_similar_episode", defaults={"_format": "json"})
     * @Method("GET")
     * @Template()
     */
    public function showRelatedEpisodesAction(Request $request, Episode $episode)
    {
        $next = $this->getDoctrine()->getRepository('MediaBundle:Episode')->findNextEpisode($episode);
        $previous = $this->getDoctrine()->getRepository('MediaBundle:Episode')->findPreviousEpisode($episode);
        $related = $this->get('oktothek_search')->searchRelatedEpisodes($episode, 5);
        $results = [];
        $results['episodes'] = $related;
        if ($next) {
            $results['next'] = $next;
        }

        if ($previous) {
            $results['prev'] = $previous;
        }
        // TODO: add tags to elasticsearch as textfield and find episodes with similar tags.
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

    /**
     * @Route("/series/{uniqID}/blog.{_format}", name="oktothek_show_series_blog", defaults={"_format": "html", "page": "1"}, requirements={"page": "\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showSeriesBlogAction(Series $series, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $posts = $paginator->paginate($em->getRepository('AppBundle:Post')->findPostsForSeriesQuery($series), $page, 5);
        $teaser = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->findNewestPosts(5, $series);
        return ['series' => $series, 'posts' => $posts, 'teasers' => $teaser];
    }

    /**
     * @Route("/series/{uniqID}/blogpost/{slug}.{_format}", name="oktothek_show_series_blogpost", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function showSeriesBlogpostAction($uniqID, $slug)
    {
        $series = $this->get('oktolab_media')->getSeries($uniqID);
        $post = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->findOneBy(['slug' => $slug]);
        return ['series' => $series, 'post' => $post];
    }

}
?>
