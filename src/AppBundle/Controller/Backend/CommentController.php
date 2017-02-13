<?php

namespace AppBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Episode;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
/**
 * Series backend controller.
 *
 * @Route("/backend/comment")
 * @Security("has_role('ROLE_USER')")
 */
class CommentController extends Controller
{
    /**
     * @Route("s", name="oktolab_backend_comment_index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $results = $request->query->get('results', 10);
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $comments = $paginator->paginate(
            $em->getRepository('AppBundle:Comment')->findComments(0, true),
            $page,
            $results
        );

        return ['comments' => $comments];
    }

    /**
     * @Route("/{comment}/show", name="oktothek_backend_comment_show")
     * @Template()
     */
    public function showAction(Comment $comment)
    {
        return ['comment' => $comment];
    }

    /**
     * @Route("/{comment}/hide", name="oktothek_backend_comment_hide")
     * @Template()
     */
    public function hideComment(Request $request, Comment $comment)
    {

        return ['form' => $form->createView()];
    }
}
