<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Page;
use AppBundle\Form\PageType;
/**
 * News controller.
 *
 * @Route("/page")
 */
class PageController extends Controller
{
    /**
     * @Route("/footer/{slug}", name="oktothek_page_footer", defaults = { "slug" = "" })
     * @Template()
     */
    public function footerAction($slug)
    {
        $pages = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page')->findAll();
        return ['pages' => $pages, 'slug' => $slug];
    }

    /**
     * @Route("/new", name="oktothek_page_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $page = new Page();
        $form = $this->createForm(new PageType(), $page);
        $form->add('submit', 'submit', ['label' => 'oktothek.page_create_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('preview', 'submit', ['label' => 'oktothek.page_preview_button']);

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
                    return $this->render('AppBundle:Page:preview.html.twig', ['page' => $page]);
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
        $form = $this->createForm(new PageType(), $page);
        $form->add('submit', 'submit', ['label' => 'oktothek.page_update_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('delete', 'submit', ['label' => 'oktothek.page_delete_button', 'attr' => ['class' => 'btn btn-danger']]);
        $form->add('preview', 'submit', ['label' => 'oktothek.page_preview_button']);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            if ($form->isValid()) { //form is valid, save or preview
                if ($form->get('submit')->isClicked()) { //save me
                    $em->persist($page);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_page');
                    return $this->redirect($this->generateUrl('oktothek_page_show', ['page' => $page->getSlug()]));
                } else { //preview
                    return $this->render('AppBundle:Page:preview.html.twig', ['page' => $page]);
                }
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_page');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{page}", name="oktothek_page_show")
     * @Template()
     */
    public function showAction($page)
    {
        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page')->findOneBy(['slug' => $page]);
        return ['page' => $page];
    }
}
