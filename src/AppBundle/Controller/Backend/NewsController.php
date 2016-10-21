<?php

namespace AppBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/backend/news")
 */
class NewsController extends Controller
{
    /**
     * @Route("/{page}", name="oktothek_backend_news_index", requirements={"page": "\d+"}, defaults={"page": 1})
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $posts = $paginator->paginate($em->getRepository('AppBundle:Post')->findAllPostQuery(), $page, 5);

        return ['posts' => $posts];
    }

    /**
     * @Route("/new", name="oktothek_backend_news_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.post_create_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('preview', SubmitType::class, ['label' => 'oktothek.page_preview_button']);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($form->get('submit')->isClicked()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($post);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_post');

                    return $this->redirect($this->generateUrl('oktothek_news'));
                } else { //preview
                    return $this->render('AppBundle:Backend\News:preview.html.twig', ['post' => $post]);
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_post');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{post}/edit", name="oktothek_backend_news_edit")
     * @Template()
     */
    public function editAction(Request $request, Post $post)
    {
        $form = $this->createForm(PostType::class(), $post);
        $form->add('delete', SubmitType::class, ['label' => 'oktothek.post_delete_button', 'attr' => ['class' => 'btn btn-danger']]);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.post_update_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('preview', SubmitType::class, ['label' => 'oktothek.page_preview_button']);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('submit')->isClicked()) { // update post
                    $em->persist($post);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_post');
                    return $this->redirect($this->generateUrl('oktothek_news'));
                } elseif ($form->get('delete')->isClicked()) { // delete post
                    $this->get('oktothek_post_service')->deletePost($post);
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_post');
                    return $this->redirect($this->generateUrl('oktothek_news'));
                } else {
                    return $this->render('AppBundle:Backend\News:preview.html.twig', ['post' => $post]);
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_post');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{post}/show", name="oktothek_backend_news_show")
     * @Template()
     */
    public function showAction(Post $post)
    {
        return ['post' => $post];
    }
}
