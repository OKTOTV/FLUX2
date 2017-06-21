<?php

namespace AppBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Page;
use AppBundle\Form\PageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * News controller.
 *
 * @Route("/backend/page")
 */
class PageController extends Controller
{
    /**
     * @Route("/", name="oktothek_backend_page_index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $pages = $paginator->paginate(
            $em->getRepository('AppBundle:Page')->findPagesQuery(),
            $request->query->get('page', 1),
            $request->query->get('results', 5)
        );

        return ['pages' => $pages];
    }

    /**
     * @Route("/new", name="oktothek_page_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.page_create_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('preview', SubmitType::class, ['label' => 'oktothek.page_preview_button']);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            if ($form->isValid()) { //form is valid, save or preview
                if ($form->get('submit')->isClicked()) { //save me
                    $em->persist($page);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_page');
                    return $this->redirect($this->generateUrl('oktothek_page_show', ['page' => $page->getSlug()]));
                } else { //preview
                    return $this->render('AppBundle:Backend\Page:preview.html.twig', ['page' => $page]);
                }
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_page');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{page}/edit", name="oktothek_page_edit")
     * @Template()
     */
    public function editAction(Request $request, Page $page)
    {
        $form = $this->createForm(PageType::class, $page);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.page_update_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('delete', SubmitType::class, ['label' => 'oktothek.page_delete_button', 'attr' => ['class' => 'btn btn-danger']]);
        $form->add('preview', SubmitType::class, ['label' => 'oktothek.page_preview_button']);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            if ($form->isValid()) { //form is valid, save or preview
                if ($form->get('submit')->isClicked()) { //save me
                    $em->persist($page);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_page');
                    return $this->redirect($this->generateUrl('oktothek_page_show', ['page' => $page->getSlug()]));
                } elseif ($form->get('delete')->isClicked()) {
                    $em->remove($page);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_page');
                    return $this->redirect($this->generateUrl('homepage'));
                } else { //preview
                    return $this->render('AppBundle:Backend\Page:preview.html.twig', ['page' => $page]);
                }
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_page');
        }

        return ['form' => $form->createView()];
    }
}
