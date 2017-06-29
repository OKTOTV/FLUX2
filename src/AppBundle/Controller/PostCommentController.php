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
use AppBundle\Entity\PostComment;
use AppBundle\Form\PostCommentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Post controller.
 *
 * @Route("/oktothek/post_comments")
 */
class PostCommentController extends Controller
{
    /**
     * @Route("/{slug}", name="oktothek_post_comments")
     * @ParamConverter("post", class="AppBundle:Post", options={"slug" = "slug"})
     * @Template()
     */
    public function indexAction(Request $request, Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $this->get('knp_paginator')->paginate(
            $em->getRepository('AppBundle:PostComment')->findCommentsForPost($post, 0, true),
            $request->query->get('page', 1),
            $request->query->get('results', 5)
        );
        $comments->setUsedRoute('oktothek_post_comments', ['uniqID' => $post->getUniqID()]);

        return ['comments' => $comments, 'post' => $post];
    }

    /**
     * @Security("has_role('ROLE_OKTOLAB_USER')")
     * @Route("/{post}/create", name="oktothek_post_comment_create")
     * @ParamConverter("post", class="AppBundle:Post", options={"slug" = "slug"})
     * @Template()
     */
    public function newCommentAction(Request $request, Post $post)
    {
        $comment = new PostComment();
        $user = $this->get('security.context')->getToken()->getUser();
        $comment->setUser($user);
        $post->addComment($comment);

        $form = $this->createForm(
            PostCommentType::class,
            $comment,
            ['action' => $this->generateUrl('oktothek_post_comment_create', ['post' => $post->getId()])]
        );
        $form->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'oktothek.comment_send_button',
                'attr' => ['class' => 'btn btn-link']
            ]
        );

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->persist($post);
                $em->flush();
                if ($request->isXmlHttpRequest()) {
                    return $this->render("AppBundle::PostComment/_comment.html.twig", ['comment' => $comment]);
                }
                return $this->redirect($this->generateUrl('oktothek_show_series_blogpost', ['slug' => $post->getSlug()]));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_comment');
            }
        }

        return ['form' => $form->createView(), 'post' => $post];
    }

    /**
     * @Security("has_role('ROLE_OKTOLAB_USER')")
     * @Route("/{slug}/{parent}/answer", name="oktothek_post_comment_answer")
     * @ParamConverter("post", class="AppBundle:Post", options={"slug" = "slug"})
     * @Template()
     */
    public function answerCommentAction(Request $request, Post $post, PostComment $parent)
    {
        $comment = new PostComment();

        $form = $this->createForm(
            PostCommentType::class,
            $comment,
            ['action' => $this->generateUrl('oktothek_post_comment_answer', ['slug' => $post->getSlug(), 'parent' => $parent->getId()])]
        );
        $form->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'oktothek.comment_send_button',
                'attr' => ['class' => 'btn btn-link']
            ]
        );

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $user = $this->get('security.context')->getToken()->getUser();
                $comment->setUser($user);
                $parent->addChild($comment);
                $post->addComment($comment);
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->persist($parent);
                $em->flush();
                return $this->redirect($this->generateUrl('oktothek_show_series_blogpost', ['slug' => $post->getSlug()]));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_comment');
            }
        }
        if ($request->isXmlHttpRequest()) {
            return $this->render("AppBundle::PostComment/_form.html.twig", ['form' => $form->createView()]);
        }
        return ['form' => $form->createView(), 'comment' => $parent, 'post' => $post];
    }

    /**
     * @Route("/edit/{comment}", name="oktothek_post_comment_edit")
     * @Template()
     */
    public function editCommentAction(Request $request, PostComment $comment)
    {
        $this->denyAccessUnlessGranted('user_edit', $comment); //symfony voter
        $commentForm = $this->createForm(PostCommentType::class, $comment, ['action' => $this->generateUrl('oktothek_edit_comment', ['comment' => $comment->getId()])]);
        $commentForm->add('submit', SubmitType::class, ['label' => 'oktothek.comment_update_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") {
            $commentForm->handleRequest($request);
            if ($commentForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.comment_update_success');
                return $this->redirect($request->headers->get('referer'));
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.comment_update_error');
            return $this->redirect($request->headers->get('referer'));
        }
        return ['form' => $commentForm->createView()];
    }
}
