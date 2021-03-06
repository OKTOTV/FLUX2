<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Post;

/**
 * News controller.
 *
 * @Route("/news")
 */
class NewsController extends Controller
{

    /**
     * @Route("/", name="oktothek_news")
     * @Template()
     */
    public function newsAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $results = $request->query->get('results', 5);

        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $posts = $paginator->paginate(
            $em->getRepository('AppBundle:Post')->findAllActivePosts(0, true),
            $page,
            $results
        );

        return ['posts' => $posts];
    }

    /**
     * @Route("/pinned", name="oktothek_news_pinned")
     * @Template()
     */
    public function pinnedNewsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Post')->findPinnedPosts();
        return ['posts' => $posts];
    }

    /**
     * @Route("/{slug}", name="oktothek_show_news")
     * @ParamConverter("slug", class="AppBundle:Post", options={"mapping": {"slug": "slug"}})
     * @Template()
     */
    public function showNewsAction(Post $slug)
    {
        return ['post' => $slug];
    }
}
