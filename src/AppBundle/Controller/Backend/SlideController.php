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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/backend/slider")
 */
class SlideController extends Controller
{
    /**
     * @Route("/index", name="oktothek_backend_slide_index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $results = $request->query->get('results', 10);
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $slides = $paginator->paginate($em->getRepository('AppBundle:Slide')->findAllSlidesQuery(), $page, $results);

        return ['slides' => $slides];
    }

    /**
     * @Route("/new", name="oktothek_backend_slide_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $slide = new Slide();
        $form = $this->createForm(SlideType::class, $slide);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.slide_create_button', 'attr' => ['class' => 'btn btn-primary']]);

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
    public function editAction(Request $request, $slide)
    {
        $slide = $this->getDoctrine()->getManager()->getRepository('AppBundle:Slide')->findSlide($slide);

        $form = $this->createForm(SlideType::class, $slide);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.slide_update_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('delete', SubmitType::class, ['label' => 'oktothek.slide_delete_button', 'attr' => ['class' => 'btn btn-danger']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($form->get('submit')->isClicked()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($slide);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_update_slide');

                    return $this->redirect($this->generateUrl('oktothek_backend_slide_index'));
                } else { //delete
                    $this->get('oktothek_slide')->deleteSlide($slide);
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_slide');
                    return $this->redirect($this->generateUrl('oktothek_backend_slide_index'));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_update_slide');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/slide_from_episode/{uniqID}", name="oktothek_backend_slide_from_episode")
     * @Template("AppBundle::backend\slide\edit.html.twig")
     */
    public function slideFromEpisode(Request $request, Episode $episode)
    {
        $slide = $this->get('oktothek_slide')->createSlideFromEpisode($episode);
        $form = $this->createForm(SlideType::class, $slide);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.slide_create_button', 'attr' => ['class' => 'btn btn-primary']]);

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
     * @Template("AppBundle::backend\slide\edit.html.twig")
     */
    public function slideFromNews(Request $request, Post $post)
    {
        $slide = $this->get('oktothek_slide')->createSlideFromNews($post);
        $form = $this->createForm(SlideType::class, $slide);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.slide_create_button', 'attr' => ['class' => 'btn btn-primary']]);

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
