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
                'attr' => ['class' => 'btn btn-link comment_submit']
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
                'attr' => ['class' => 'btn btn-link comment_submit answer']
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
                if ($request->isXmlHttpRequest()) {
                    return $this->render("AppBundle::episode_comment/_comment.html.twig", ['comment' => $comment]);
                }
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
}
