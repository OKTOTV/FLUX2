<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Episode;
// use AppBundle\Entity\Post;
use AppBundle\Form\CommentType;
use AppBundle\Entity\Comment;

/**
 * Episode controller.
 *
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route("/episode/{uniqID}", name="oktothek_comment_episode")
     * @Template()
     */
    public function commentEpisodeAction(Request $request, Episode $episode)
    {
        $comment = new Comment();
        $commentForm = $this->createForm(new CommentType(), $comment, ['action' => $this->generateUrl('oktothek_comment_episode', ['uniqID' => $episode->getUniqID()])]);

        if ($request->getMethod() == "POST") {
            $commentForm->handleRequest($request);
            if ($commentForm->isValid()) {
                $comment->setUser($this->get('security.context')->getToken()->getUser());
                $episode->addComment($comment);
                $em = $this->getDoctrine()->getManager();
                $em->persist($episode);
                $em->persist($comment);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.comment_create_success');
                return $this->redirect($request->getReferer());
                // return $this->redirect($this->generateUrl('oktothek_show_episode', ['uniqID' => $episode->getUniqID()]));
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.comment_create_error');
        }
        return ['commentForm' => $commentForm->createView()];
    }

    /**
     * @Route("/answer/{comment}", name="oktothek_answer_comment")
     * @Template()
     * TODO: redirect to previous route
     */
    public function answerCommentAction(Request $request, Comment $comment)
    {
        $answer = new Comment();
        $commentForm = $this->createForm(new CommentType(), $answer, ['action' => $this->generateUrl('oktothek_answer_comment', ['comment' => $comment->getId()])]);

        if ($request->getMethod() == "POST") {
            $commentForm->handleRequest($request);
            if ($commentForm->isValid()) {
                $answer->setUser($this->get('security.context')->getToken()->getUser());
                $answer->setParent($comment);
                $comment->addChild($answer);
                $em = $this->getDoctrine()->getManager();
                $em->persist($episode);
                $em->persist($answer);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.comment_create_success');
                return $this->redirect($request->getReferer());
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.comment_create_error');
        }
        return ['commentForm' => $commentForm->createView()];
    }
}
