<?php

namespace AppBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Comment;
use AppBundle\Form\Backend\RemoveCommentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
     * @Route("/{comment}/remove", name="oktothek_backend_comment_remove")
     * @Template()
     */
    public function removeAction(Request $request, Comment $comment)
    {
        $form = $this->createForm(RemoveCommentType::class, $comment);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.slide_update_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('delete', SubmitType::class, ['label' => 'oktothek.slide_delete_button', 'attr' => ['class' => 'btn btn-danger']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($form->get('submit')->isClicked()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comment);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_update_comment');

                    return $this->redirect($this->generateUrl('oktothek_backend_comment_show', ['comment' => $comment->getId()]));
                } else { //delete
                    $this->get('oktothek_slide')->deleteSlide($comment);
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_comment');
                    return $this->redirect($this->generateUrl('oktolab_backend_comment_index'));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_update_comment');
            }
        }

        return ['form' => $form->createView()];
    }
}
