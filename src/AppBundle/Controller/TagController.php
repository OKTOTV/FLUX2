<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Tag;

/**
 * Tag controller.
 *
 * @Route("/tag")
 */
class TagController extends Controller
{
    /**
     * @Route("/menu")
     * @Template()
     */
    public function menuTagsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository('AppBundle:Tag')->findHighlightedTags();
        return ['tags' => $tags];
    }

    /**
     * @Route("/ajax", name="oktothek_tag_ajax")
     * @Method({"GET", "POST"})
     */
    public function ajaxTagAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            if ($request->getMethod() =="GET") {
                $tags = $em->getRepository('AppBundle:Tag')->findAll();
                $json = [];
                foreach ($tags as $tag) {
                    $json[] = $tag->getText();
                }
                return new Response(json_encode($json));
            } else { //Posts tag
                $action = $request->request->get('action');
                if ($action = "add") { // add new tag
                    $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['text' => $request->request->get('text')]);
                    if (!$tag) {
                        $tag = new Tag();
                        $tag->setText($request->request->get('text'));
                        $em->persist($tag);
                        $em->flush();
                    }
                    return new Response();
                } else { // delete tag
                    $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['text' => $request->request->get('text')]);
                    $em->remove($tag);
                    $em->flush();

                    return new Response();
                }
            }
        }
        return $this->redirect('oktothek_tag_index');
    }

    /**
     * @Route("/{slug}", name="oktothek_tag_show")
     * @ParamConverter("slug", class="AppBundle:Tag", options={"mapping": {"slug": "slug"}})
     * @Template()
     */
    public function tagAction(Tag $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Tag');
        return [
            'tag' => $slug,
            'seriess'    => $repo->findSeriesWithTag($slug),
            'playlists' => $repo->findPlaylistsWithTag($slug)
        ];
    }

    /**
     * @Route("/{slug}/episodes/{page}", name="oktothek_tag_episode_page", defaults={"page" = "1"}, requirements={"page" = "\d+"})
     * @Template("AppBundle::Tag/Pager/episodeTagPager.html.twig")
     */
    public function episodeTagPagerAction($slug, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['slug' => $slug]);
        $paginator = $this->get('knp_paginator');
        $episodes = $paginator->paginate($em->getRepository('AppBundle:Tag')->findEpisodesWithTag($tag, 0, true), $page, 5);
        return ['tag' => $tag, 'episodes' => $episodes];
    }

    /**
     * @Route("/{slug}/series/{page}", name="oktothek_tag_series_page", defaults={"page" = "1"}, requirements={"page" = "\d+"})
     * @Template("AppBundle::Tag/Pager/seriesTagPager.html.twig")
     */
    public function seriesTagPagerAction($slug, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['slug' => $slug]);
        $paginator = $this->get('knp_paginator');
        $seriess = $paginator->paginate($em->getRepository('AppBundle:Tag')->findSeriesWithTag($tag, 0, true), $page, 5);
        return ['tag' => $tag, 'seriess' => $seriess];
    }

    /**
     * @Route("/{slug}/posts/{page}", name="oktothek_tag_posts_page", defaults={"page" = "1"}, requirements={"page" = "\d+"})
     * @Template("AppBundle::Tag/Pager/postsTagPager.html.twig")
     */
    public function postsTagPagerAction($slug, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['slug' => $slug]);
        $paginator = $this->get('knp_paginator');
        $episodes = $paginator->paginate($em->getRepository('AppBundle:Tag')->findPostsWithTag($tag, 0, true), $page, 5);
        return ['tag' => $tag, 'posts' => $episodes];
    }

    /**
     * @Route("/{slug}/pages/{page}", name="oktothek_tag_pages_page", defaults={"page" = "1"}, requirements={"page" = "\d+"})
     * @Template("AppBundle::Tag/Pager/pagesTagPager.html.twig")
     */
    public function pagesTagPagerAction($slug, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['slug' => $slug]);
        $paginator = $this->get('knp_paginator');
        $episodes = $paginator->paginate($em->getRepository('AppBundle:Tag')->findPagesWithTag($tag, 0, true), $page, 5);
        return ['tag' => $tag, 'pages' => $episodes];
    }

    /**
     * @Route("/{slug}/playlists/{page}", name="oktothek_tag_playlists_page", defaults={"page" = "1"}, requirements={"page" = "\d+"})
     * @Template("AppBundle::Tag/Pager/playlistsTagPager.html.twig")
     */
    public function playlistsTagPagerAction($slug, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['slug' => $slug]);
        $paginator = $this->get('knp_paginator');
        $episodes = $paginator->paginate($em->getRepository('AppBundle:Tag')->findPlaylistsWithTag($tag, 0, true), $page, 5);
        return ['tag' => $tag, 'playlists' => $episodes];
    }
}
