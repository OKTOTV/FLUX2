<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Comment;
use AppBundle\Form\DeleteCommentType;
use AppBundle\Form\EditCommentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Comment controller.
 *
 * @Route("/oktothek/comments")
 */
class CommentController extends Controller
{
    /**
     * @Route("/delete/{comment}", name="oktothek_comment_delete")
     * @Template()
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $this->denyAccessUnlessGranted('user_delete_comment', $comment);
        $form = $this->createForm(DeleteCommentType::class, $comment);
        $form->add(
            'delete',
            SubmitType::class,
            [
                'label' => 'oktothek.comment_delete_button',
                'attr' => ['class' => 'btn btn-default']
            ]
        );

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $commentedObject = $comment->getCommentedObject();
                $commentedObject->removeComment($comment);
                $em->remove($comment);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'oktothek.comment_delete_success'
                );
                return $this->redirect($this->generateUrl('user_comment_index'));
            }

            $this->get('session')->getFlashBag()->add(
                'error',
                'oktothek.comment_delete_error'
            );
        }
        return ['form' => $form->createView(), 'comment' => $comment];
    }

    /**
     * @Route("/edit/{comment}", name="oktothek_comment_edit")
     * @Template()
     */
    public function editAction(Request $request, Comment $comment)
    {
        $this->denyAccessUnlessGranted('user_edit', $comment); //symfony voter
        $commentForm = $this->createForm(
            EditCommentType::class,
            $comment,
            [
                'action' => $this->generateUrl(
                    'oktothek_comment_edit',
                    [
                        'comment' => $comment->getId(),
                        'redirect_me' => $request->query->get('redirect_me', $this->generateUrl('user_comment_index'))
                    ]
                )
            ]
        );

        if ($request->getMethod() == "POST") {
            $commentForm->handleRequest($request);
            if ($commentForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $object = $comment->getCommentedObject();
                if ($commentForm->get('submit')->isClicked()) {
                    $em->persist($comment);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add(
                        'success',
                        'oktothek.comment_update_success'
                    );
                    return $this->redirect(
                        $request->query->get('redirect_me', $this->generateUrl('user_comment_index'))
                    );
                } elseif ($commentForm->get('delete')->isClicked()) {
                    $object->removeComment($comment);
                    $em->remove($comment);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add(
                        'success',
                        'oktothek.comment_delete_success'
                    );
                    return $this->redirect(
                        $request->query->get('redirect_me', $this->generateUrl('user_comment_index'))
                    );
                }
            }
            $this->get('session')->getFlashBag()->add(
                'error',
                'oktothek.comment_update_error'
            );
        }
        return ['form' => $commentForm->createView()];
    }

    //TODO: create and answer comment with redirect as request variable, service to create Comment for object
}
