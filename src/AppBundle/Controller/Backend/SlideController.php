<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Slide;
use AppBundle\Entity\Episode;
use AppBundle\Entity\Post;
use AppBundle\Form\Backend\SlideType;

/**
 * @Route("/backend/slider")
 */
class SlideController extends Controller
{
    /**
     * @Route("/index", name="oktothek_backend_slide_index", requirements={"page": "\d+"}, defaults={"page": 1})
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $slides = $paginator->paginate($em->getRepository('AppBundle:Slide')->findAllSlidesQuery(), $page, 10);

        return ['slides' => $slides];
    }

    /**
     * @Route("/new", name="oktothek_backend_slide_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $slide = new Slide();
        $form = $this->createForm(new SlideType(), $slide);
        $form->add('submit', 'submit', ['label' => 'oktothek.slide_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($slide);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_slide');

                return $this->redirect($this->generateUrl('oktothek_backend_slide_index'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_slide');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/edit/{slide}", name="oktothek_backend_slide_edit")
     * @Template()
     */
    public function editAction(Request $request, Slide $slide)
    {
        $form = $this->createForm(new SlideType(), $slide);
        $form->add('submit', 'submit', ['label' => 'oktothek.slide_update_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('delete', 'submit', ['label' => 'oktothek.slide_delete_button', 'attr' => ['class' => 'btn btn-danger']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($form->get('submit')->isClicked()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($slide);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_update_slide');

                    return $this->redirect($this->generateUrl('oktothek_news'));
                } else { //delete
                    $this->get('oktothek_slide')->deleteSlide($slide);
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_slide');
                    return $this->redirect($this->generateUrl('oktothek_news'));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_update_slide');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/slide_from_episode/{uniqID}", name="oktothek_backend_slide_from_episode")
     * @Template("AppBundle:Backend\Slide\edit.html.twig")
     */
    public function slideFromEpisode(Request $request, Episode $episode)
    {
        $slide = $this->get('oktothek_slide')->createSlideFromEpisode($episode);
        $form = $this->createForm(new SlideType(), $slide);
        $form->add('submit', 'submit', ['label' => 'oktothek.slide_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($slide);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_slide');

                return $this->redirect($this->generateUrl('oktothek_backend_slide_index'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_slide');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/slide_from_news/{slug}", name="oktothek_backend_slide_from_news")
     * @Template("AppBundle:Backend\Slide\edit.html.twig")
     */
    public function slideFromNews(Request $request, Post $post)
    {
        $slide = $this->get('oktothek_slide')->createSlideFromNews($post);
        $form = $this->createForm(new SlideType(), $slide);
        $form->add('submit', 'submit', ['label' => 'oktothek.slide_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($slide);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_slide');

                return $this->redirect($this->generateUrl('oktothek_backend_slide_index'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_slide');
            }
        }

        return ['form' => $form->createView()];
    }
}
