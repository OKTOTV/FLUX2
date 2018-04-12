<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Episode;
use AppBundle\Entity\EpisodeComment;
use AppBundle\Form\EpisodeCommentType;
use AppBundle\Form\DeleteCommentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Episode controller.
 *
 * @Route("/oktothek/episode_comments")
 */
class EpisodeCommentController extends Controller
{
    /**
     * @Route("/{uniqID}", name="oktothek_episode_comments")
     * @Template()
     */
    public function indexAction(Request $request, $uniqID)
    {
        $episode = $this->get('oktolab_media')->getEpisode($uniqID);
        $em = $this->getDoctrine()->getManager();
        $comments = $this->get('knp_paginator')->paginate(
            $em->getRepository('AppBundle:EpisodeComment')->findCommentsForEpisode($episode, 0, true),
            $request->query->get('page', 1),
            $request->query->get('results', 5)
        );
        $comments->setUsedRoute('oktothek_episode_comments', ['uniqID' => $episode->getUniqID()]);
        // $comments->setParam('series', $series->getId());

        return ['comments' => $comments, 'episode' => $episode];
    }

    /**
     * @Security("has_role('ROLE_OKTOLAB_USER')")
     * @Route("/{episode}/create", name="oktothek_episode_comment_create")
     * @Template()
     */
    public function newCommentAction(Request $request, Episode $episode)
    {
        $comment = new EpisodeComment();
        $user = $this->getUser();
        $comment->setUser($user);
        $episode->addComment($comment);

        $form = $this->createForm(
            EpisodeCommentType::class,
            $comment,
            ['action' => $this->generateUrl('oktothek_episode_comment_create', ['episode' => $episode->getId()])]
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
                $em->persist($episode);
                $em->flush();
                $this->get('oktothek_notification_service')->onCommentOnEpisode($comment);
                if ($request->isXmlHttpRequest()) {
                    return $this->render("AppBundle::episode_comment/_comment.html.twig", ['comment' => $comment]);
                }
                return $this->redirect($this->generateUrl('oktothek_show_episode', ['uniqID' => $episode->getUniqID()]));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_comment');
            }
        }

        return ['form' => $form->createView(), 'episode' => $episode];
    }

    /**
     * @Security("has_role('ROLE_OKTOLAB_USER')")
     * @Route("/{uniqID}/{parent}/answer", name="oktothek_episode_comment_answer")
     * @Template()
     */
    public function answerCommentAction(Request $request, $uniqID, EpisodeComment $parent)
    {
        $episode = $this->get('oktolab_media')->getEpisode($uniqID);
        $comment = new EpisodeComment();

        $form = $this->createForm(
            EpisodeCommentType::class,
            $comment,
            ['action' => $this->generateUrl('oktothek_episode_comment_answer', ['uniqID' => $episode->getUniqID(), 'parent' => $parent->getId()])]
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
                $user = $this->getUser();
                $comment->setUser($user);
                $parent->addChild($comment);
                $episode->addComment($comment);
                $em = $this->getDoctrine()->getManager();
                $em->persist($episode);
                $em->persist($parent);
                $em->flush();
                $this->get('oktothek_notification_service')->onCommentOnEpisode($comment);
                return $this->redirect($this->generateUrl('oktothek_show_episode', ['uniqID' => $episode->getUniqID()]));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_comment');
            }
        }
        if ($request->isXmlHttpRequest()) {
            return $this->render("AppBundle::episode_comment/_form.html.twig", ['form' => $form->createView()]);
        }
        return ['form' => $form->createView(), 'comment' => $parent, 'episode' => $episode];
    }

    /**
     * @Route("/edit/{comment}", name="oktothek_episode_comment_edit")
     * @Template()
     */
    public function editCommentAction(Request $request, EpisodeComment $comment)
    {
        $this->denyAccessUnlessGranted('user_edit', $comment); //symfony voter
        $commentForm = $this->createForm(EpisodeCommentType::class, $comment, ['action' => $this->generateUrl('oktothek_episode_comment_edit', ['comment' => $comment->getId()])]);
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
                    return $this->redirect($this->generateUrl('oktothek_show_episode', ['uniqID' => $comment()->getEpisode()->getUniqID()]));
                } elseif ($commentForm->get('delete')->isClicked()) {
                    $episode = $comment->getEpisode();
                    $episode->removeComment($comment);
                    // $em->persist($episode);
                    $em->remove($comment);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.comment_delete_success');
                    return $this->redirect($this->generateUrl('oktothek_show_episode', ['uniqID' => $episode->getUniqID()]));
                }
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.comment_update_error');
            return $this->redirect($request->headers->get('referer'));
        }
        return ['form' => $commentForm->createView()];
    }

    /**
     * @Route("/delete/{comment}", name="oktothek_episode_comment_delete")
     * @Template()
     */
    public function deleteCommentAction(Request $request, EpisodeComment $comment)
    {
        $this->denyAccessUnlessGranted('user_delete_comment', $comment);
        $form = $this->createForm(DeleteCommentType::class, $comment);
        $form->add('delete', SubmitType::class, ['label' => 'oktothek.episode_comment_delete_button', 'attr' => ['class' => 'btn btn-default']]);

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
