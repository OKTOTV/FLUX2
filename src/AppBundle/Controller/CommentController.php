<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\CommentType;
use AppBundle\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Episode controller.
 *
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * @Security("has_role('ROLE_OKTOLAB_USER')")
     * @Route("/new/{uniqID}", name="oktothek_comment")
     * @Template()
     */
    public function commentEpisodeAction(Request $request, $uniqID)
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment, ['action' => $this->generateUrl('oktothek_comment', ['uniqID' => $uniqID])]);
        $commentForm->add('submit', SubmitType::class, ['label' => 'oktothek.comment_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") {
            $commentForm->handleRequest($request);
            if ($commentForm->isValid()) {
                $comment->setUser($this->get('security.context')->getToken()->getUser());
                $comment->setReferer($uniqID);
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.comment_create_success');
                return $this->redirect($request->headers->get('referer'));
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.comment_create_error');
            return $this->redirect($request->headers->get('referer'));
        }
        return ['commentForm' => $commentForm->createView()];
    }

    /**
     * @Security("has_role('ROLE_OKTOLAB_USER')")
     * @Route("/answer/{comment}", name="oktothek_answer_comment")
     * @Template()
     */
    public function answerCommentAction(Request $request, Comment $comment)
    {
        $answer = new Comment();
        $commentForm = $this->createForm(CommentType::class, $answer, ['action' => $this->generateUrl('oktothek_answer_comment', ['comment' => $comment->getId()])]);
        $commentForm->add('submit', SubmitType::class, ['label' => 'oktothek.comment_reply_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") {
            $commentForm->handleRequest($request);
            if ($commentForm->isValid()) {
                $answer->setUser($this->get('security.context')->getToken()->getUser());
                $answer->setReferer($comment->getReferer());
                $answer->setParent($comment);
                $comment->addChild($answer);
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->persist($answer);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.comment_create_success');
                return $this->redirect($request->headers->get('referer'));
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.comment_create_error');
        }
        return ['commentForm' => $commentForm->createView()];
    }

    /**
     * @Route("/episode/{uniqID}", name="oktothek_episode_comment_pager")
     * @Template()
     */
    public function episodeCommentPagerAction(Request $request, $uniqID)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT c FROM AppBundle:Comment c WHERE c.referer = :uniqID AND c.parent IS NULL ORDER BY c.createdAt DESC";
        $query = $em->createQuery($dql);
        $query->setParameter('uniqID', $uniqID);
        $paginator = $this->get('knp_paginator');
        $comments = $paginator->paginate($query, $request->query->get('page', 1), 5);
        $comments->setUsedRoute('oktothek_episode_comment_pager', ['uniqID' => $uniqID]);

        return ['comments' => $comments, 'uniqID' => $uniqID];
    }

    // /**
    //  * @Route("/edit/{comment}", name="oktothek_edit_comment")
    //  * @Template()
    //  */
    // public function editCommentAction(Request $request, Comment $comment)
    // {
    //     $this->denyAccessUnlessGranted('user_edit', $comment); //symfony voter
    //     $commentForm = $this->createForm(CommentType::class, $comment, ['action' => $this->generateUrl('oktothek_edit_comment', ['comment' => $comment->getId()])]);
    //     $commentForm->add('submit', SubmitType::class, ['label' => 'oktothek.comment_update_button', 'attr' => ['class' => 'btn btn-primary']]);
    //
    //     if ($request->getMethod() == "POST") {
    //         $commentForm->handleRequest($request);
    //         if ($commentForm->isValid()) {
    //             $em = $this->getDoctrine()->getManager();
    //             $em->persist($comment);
    //             $em->flush();
    //             $this->get('session')->getFlashBag()->add('success', 'oktothek.comment_update_success');
    //             return $this->redirect($request->headers->get('referer'));
    //         }
    //         $this->get('session')->getFlashBag()->add('error', 'oktothek.comment_update_error');
    //         return $this->redirect($request->headers->get('referer'));
    //     }
    //     return ['commentForm' => $commentForm->createView()];
    // }

    /**
     * @Route("/{uniqID}/{page}", name="oktothek_comment_pager", requirements={"page": "\d+"}, defaults={"page":1})
     * @Template()
     */
    public function commentPagerAction($uniqID, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT c FROM AppBundle:Comment c WHERE c.referer = :uniqID AND c.parent IS NULL ORDER BY c.createdAt DESC";
        $query = $em->createQuery($dql);
        $query->setParameter('uniqID', $uniqID);
        $paginator = $this->get('knp_paginator');
        $comments = $paginator->paginate($query, $page, 5);
        $comments->setUsedRoute('oktothek_comment_pager', ['uniqID' => $uniqID]);

        return ['comments' => $comments, 'uniqID' => $uniqID];
    }
}
