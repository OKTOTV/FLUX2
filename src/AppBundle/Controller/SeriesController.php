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
 * Series controller.
 *
 * @Route("/series")
 */
class SeriesController extends Controller
{
    /**
     * @Route("/{uniqID}/episodes_with_tags_ajax", name="oktothek_series_episodes_with_tags_ajax")
     * @Method({"POST", "GET"})
     * @Template()
     */
    public function episodesWithTagsAjaxAction(Request $request, Series $series)
    {
        $em = $this->getDoctrine()->getManager();
        $episodes = [];
        if ($request->getMethod() == "POST") {
            if ($request->request->get('tag') == "all") {
                $episodes = $em->getRepository('AppBundle:Series')->findNewestEpisodesForSeries($series);
                return $this->render('AppBundle::Series/episodeStackOpen.html.twig', ['episodes' => $episodes]);
            } else {
                $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['slug' => $request->request->get('tag')]);
                $episodes = $em->getRepository('AppBundle:Series')->findEpisodesWithTag($series, $tag);
                return $this->render('AppBundle::Series/episodeStackOpen.html.twig', ['episodes' => $episodes]);
            }
        } else {
            $episodes = $em->getRepository('AppBundle:Series')->findNewestEpisodesForSeries($series);
        }
        $tags = $em->getRepository('AppBundle:Series')->getSeriesTags($series);
        return ['episodes' => $episodes, 'series' => $series, 'series_tags' => $tags];
    }

    /**
     * @Route("/{uniqID}", name="oktothek_show_series")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Series $series)
    {
        $posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->findNewestPosts(5, $series);
        return ['series' => $series, 'teasers' => $posts];
    }

    /**
     * @Route("/{uniqID}/blog.{_format}", name="oktothek_show_series_blog", defaults={"_format": "html", "page": "1"}, requirements={"page": "\d+"})
     * @Method("GET")
     * @Template()
     */
    public function blogIndexAction(Series $series, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $posts = $paginator->paginate($em->getRepository('AppBundle:Post')->findPostsForSeriesQuery($series), $page, 5);
        $teaser = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->findNewestPosts(5, $series);
        return ['series' => $series, 'posts' => $posts, 'teasers' => $teaser];
    }

    /**
     * @Route("/{uniqID}/post/{slug}.{_format}", name="oktothek_show_series_blogpost", defaults={"_format": "html"})
     * @Route("/{webtitle}/post/{slug}.{_format}", name="oktothek_series_blog_show", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function blogShowAction(Series $series, $slug)
    {
        $post = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->findOneBy(['slug' => $slug]);
        $teaser = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->findNewestPosts(5, $series);
        return ['series' => $series, 'post' => $post, 'teasers' => $teaser];
    }

    /**
     * @Route("/{uniqID}/episodes.{_format}", name="oktothek_show_series_episodes", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function episodesAction(Request $request, Series $series)
    {
        $page = $request->query->get('page', 1);
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $episodes = $paginator->paginate($em->getRepository('AppBundle:Series')->findActiveEpisodes($series, true), $page, 12);
        return ['episodes' => $episodes, 'series' => $series];
    }

    /**
     * @Route("/{uniqID}/playlists.{_format}", name="oktothek_show_series_playlists", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function playlistsAction(Request $request, Series $series)
    {
        $page = $request->query->get('page', 1);
        $results = $request->query->get('results', 12);
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $playlists = $paginator->paginate($em->getRepository('AppBundle:Playlist')->findPlaylistsForSeries($series, true), $page, $results);
        return ['playlists' => $playlists, 'series' => $series];
    }
}
