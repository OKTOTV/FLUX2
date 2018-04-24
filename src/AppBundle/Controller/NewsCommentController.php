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
 * @Route("/oktothek/news_comments")
 */
class NewsCommentController extends Controller
{
    /**
     * @Route("/{slug}", name="oktothek_news_comments")
     * @Template()
     */
    public function indexAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findOneBy(['slug' => $slug]);
        $comments = $this->get('knp_paginator')->paginate(
            $em->getRepository('AppBundle:PostComment')->findCommentsForPost($post, 0, true),
            $request->query->get('page', 1),
            $request->query->get('results', 5)
        );
        $comments->setUsedRoute('oktothek_news_comments', ['uniqID' => $post->getUniqID()]);

        return ['comments' => $comments, 'post' => $post];
    }

    /**
     * @Security("has_role('ROLE_OKTOLAB_USER')")
     * @Route("/{post}/create", name="oktothek_news_comment_create")
     * @Template()
     */
    public function newCommentAction(Request $request, Post $post)
    {
        $comment = new PostComment();
        $user = $this->getUser();
        $comment->setUser($user);
        $post->addComment($comment);

        $form = $this->createForm(
            PostCommentType::class,
            $comment,
            ['action' => $this->generateUrl('oktothek_news_comment_create', ['post' => $post->getId()])]
        );
        $form->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'oktothek.comment_send_button',
                'attr' => ['class' => 'btn btn-default']
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
                    return $this->render("AppBundle::Post_Comment/_comment.html.twig", ['comment' => $comment]);
                }
                return $this->redirect($this->generateUrl('oktothek_show_news', ['slug' => $post->getSlug()]));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_comment');
            }
        }

        return ['form' => $form->createView(), 'post' => $post];
    }

    /**
     * @Security("has_role('ROLE_OKTOLAB_USER')")
     * @Route("/{parent}/answer", name="oktothek_news_comment_answer")
     * @Template()
     */
    public function answerCommentAction(Request $request, PostComment $parent)
    {
        $comment = new PostComment();

        $form = $this->createForm(
            PostCommentType::class,
            $comment,
            ['action' => $this->generateUrl('oktothek_news_comment_answer', ['parent' => $parent->getId()])]
        );
        $form->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'oktothek.comment_send_button',
                'attr' => ['class' => 'btn btn-default answer']
            ]
        );

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $user = $this->getUser();
                $comment->setUser($user);
                $parent->addChild($comment);
                $parent->getPost()->addComment($comment);
                $em = $this->getDoctrine()->getManager();
                $em->persist($parent->getPost());
                $em->persist($parent);
                $em->flush();
                if ($request->isXmlHttpRequest()) {
                    return $this->render("AppBundle::News_Comment/_answer.html.twig", ['comment' => $comment]);
                }
                return $this->redirect($this->generateUrl('oktothek_show_news', ['slug' => $parent->getPost()->getSlug()]));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_comment');
            }
        }
        if ($request->isXmlHttpRequest()) {
            return $this->render("AppBundle::Post_Comment/_form.html.twig", ['form' => $form->createView()]);
        }
        return ['form' => $form->createView(), 'comment' => $parent, 'post' => $parent->getPost()];
    }

    /**
     * @Route("/edit/{comment}", name="oktothek_news_comment_edit")
     * @Template()
     */
    public function editCommentAction(Request $request, PostComment $comment)
    {
        $this->denyAccessUnlessGranted('user_edit', $comment); //symfony voter
        $commentForm = $this->createForm(PostCommentType::class, $comment, ['action' => $this->generateUrl('oktothek_news_comment_edit', ['comment' => $comment->getId()])]);
        $commentForm->add('submit', SubmitType::class, ['label' => 'oktothek.comment_update_button', 'attr' => ['class' => 'btn btn-primary']]);
        $commentForm->add('delete', SubmitType::class, ['label' => 'oktothek.comment_delete_button', 'attr' => ['class' => 'btn btn-link']]);

        if ($request->getMethod() == "POST") {
            $commentForm->handleRequest($request);
            if ($commentForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($commentForm->get('submit')->isClicked()) {
                    $em->persist($comment);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.comment_update_success');
                    return $this->redirect($this->generateUrl('oktothek_show_news', ['slug' => $comment->getPost()->getSlug()]));
                } elseif ($commentForm->get('delete')->isClicked()) {
                    $post = $comment->getPost();
                    $post->removeComment($comment);
                    $em->remove($comment);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.comment_delete_success');
                    return $this->redirect($this->generateUrl('oktothek_show_news', ['slug' => $post->getSlug()]));
                }
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.comment_update_error');
            return $this->redirect($this->generateUrl('oktothek_show_news', ['slug' => $comment->getPost()->getSlug()]));
        }
        return ['form' => $commentForm->createView()];
    }

    /**
     * @Route("/delete/{comment}", name="oktothek_news_comment_delete")
     * @Template()
     */
    public function deleteCommentAction(Request $request, EpisodeComment $comment)
    {
        $this->denyAccessUnlessGranted('user_delete_comment', $comment);
        $form = $this->createForm(DeleteCommentType::class, $comment);
        $form->add('delete', SubmitType::class, ['label' => 'oktothek.news_comment_delete_button', 'attr' => ['class' => 'btn btn-default']]);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $episode = $comment->getEpisode();
                $episode->removeComment($comment);
                $em->remove($comment);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.comment_delete_success');
                return $this->redirect($this->generateUrl('user_comment_index'));
            }

            $this->get('session')->getFlashBag()->add('error', 'oktothek.comment_update_error');
        }
        return ['form' => $form->createView(), 'comment' => $comment];
    }
}
