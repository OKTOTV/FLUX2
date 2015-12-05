<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
/**
 * News controller.
 *
 * @Route("/news")
 */
class NewsController extends Controller
{

    /**
     * @Route("/{page}", name="oktothek_news", requirements={"page": "\d+"}, defaults={"page": 1})
     * @Template()
     */
    public function newsAction($page)
    {
        //TODO: service!
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM AppBundle:Post p WHERE p.isActive = :active AND p.onlineAt < :now";
        $query = $em->createQuery($dql);
        $query->setParameter('active', true);
        $query->setParameter('now', new \DateTime());
        $paginator = $this->get('knp_paginator');
        $posts = $paginator->paginate($query, $page, 10);

        return ['posts' => $posts];
    }

    /**
     * @Route("/new", name="oktothek_new_news")
     * @Template()
     */
    public function newNewsAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(new PostType(), $post);
        $form->add('submit', 'submit', ['label' => 'oktothek.post_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_post');

                return $this->redirect($this->generateUrl('oktothek_news'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_post');
            }
        }

        return ['form' => $form->createView()];
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

    /**
     * @Route("/{post}/edit", name="oktothek_edit_news")
     * @Template()
     */
    public function editNewsAction(Request $request, Post $post)
    {
        $form = $this->createForm(new PostType(), $post);
        $form->add('delete', 'submit', ['label' => 'oktothek.post_delete_button', 'attr' => ['class' => 'btn btn-danger']]);
        $form->add('submit', 'submit', ['label' => 'oktothek.post_update_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('submit')->isClicked()) { // update post
                    $em->persist($post);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_post');
                    return $this->redirect($this->generateUrl('oktothek_news'));
                } else { // delete post
                    $this->get('oktothek_post_service')->deletePost($post);
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_post');
                    return $this->redirect($this->generateUrl('oktothek_news'));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_post');
            }
        }

        return ['form' => $form->createView()];
    }
}
