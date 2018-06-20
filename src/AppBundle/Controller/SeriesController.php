<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Post;
use AppBundle\Entity\Series;
use AppBundle\Entity\Tag;

/**
 * Series controller.
 *
 * @Route("/series")
 */
class SeriesController extends Controller
{

    /**
     * @Route("/index.{_format}", name="oktothek_show_series_index", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Series');
        $paginator = $this->get('knp_paginator');
        $seriess = $paginator->paginate(
            $repo->findActiveSeries(true),
            $request->query->get('page', 1),
            $request->query->get('results', 24)
        );

        return ['seriess' => $seriess];
    }


    /**
     * @Route("/post/{slug}.{_format}", name="oktothek_show_series_blogpost", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function blogShowAction($slug)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post');
        $post = $repo->findActivePostBySlug($slug);
        return [
            'post' => $post,
            'teasers' => $repo->findNewestPosts(5, $post->getSeries())
        ];
    }

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
                return $this->render(
                    'AppBundle::series/episodeStackOpen.html.twig',
                    [
                        'episodes' => $episodes,
                        'pager_url' => $this->generateUrl(
                            'oktothek_show_series_episodes',
                            ['uniqID' => $series->getUniqID()]
                        )
                    ]
                );
            } else {
                $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['slug' => $request->request->get('tag')]);
                $episodes = $em->getRepository('AppBundle:Series')->findEpisodesWithTag($series, $tag);
                return $this->render(
                    'AppBundle::series/episodeStackOpen.html.twig',
                    [
                        'episodes' => $episodes,
                        'selected_tag' => $tag,
                        'pager_url' => $this->generateUrl(
                            'oktothek_show_series_episodes_with_tag',
                            [
                                'uniqID' => $series->getUniqID(),
                                'tag' => $tag->getId()
                            ]
                        )
                    ]
                );
            }
        } else {
            $episodes = $em->getRepository('AppBundle:Series')->findNewestEpisodesForSeries($series);
        }
        $tags = $em->getRepository('AppBundle:Series')->getSeriesTags($series);
        return ['episodes' => $episodes, 'series' => $series, 'series_tags' => $tags];
    }

    /**
     * @Route("/{uniqID}.{_format}", name="oktothek_show_series", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Series $series)
    {
        if (!$series->getIsActive()) {
             throw $this->createNotFoundException();
        }
        $posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->findNewestPosts(5, $series);
        $newest_episode = $this->getDoctrine()->getManager()->getRepository('AppBundle:Series')->findNewestEpisodesForSeries($series, 1)[0];
        return ['series' => $series, 'teasers' => $posts, 'newest_episode' => $newest_episode];
    }

    /**
     * @Route("/{uniqID}/blog.{_format}", name="oktothek_show_series_blog", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function blogIndexAction(Request $request, Series $series)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $posts = $paginator->paginate(
            $em->getRepository('AppBundle:Post')->findActivePostsForSeries($series, true),
            $request->query->get('page', 1),
            $request->query->get('results', 5)
        );

        $teaser = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->findNewestPosts(5, $series);
        return ['series' => $series, 'posts' => $posts, 'teasers' => $teaser];
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
     * @Route("/{uniqID}/episodes_with_tag/{tag}.{_format}", name="oktothek_show_series_episodes_with_tag", defaults={"_format": "html"})
     * @Method("GET")
     * @Template()
     */
    public function episodesWithTagAction(Request $request, Series $series, Tag $tag)
    {
        $page = $request->query->get('page', 1);
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $episodes = $paginator->paginate($em->getRepository('AppBundle:Series')->findActiveEpisodesWithTag($series, $tag, true), $page, 12);
        return ['episodes' => $episodes, 'series' => $series, 'tag' => $tag];
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
