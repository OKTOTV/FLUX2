<?php

namespace AppBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Tag;
use AppBundle\Entity\TagCollection;
use AppBundle\Form\TagType;
use AppBundle\Form\TagCollectionType;

/**
 * Tag controller.
 *
 * @Route("/backend/tag")
 */
class TagController extends Controller
{
    /**
     * @Route("/{page}", name="oktothek_tag_index", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page = 1)
    {
        $paginator = $this->get('knp_paginator');
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Tag');
        $tags = $paginator->paginate($repo->findPopularTags(0, true), $page, 10);
        return ['tags' => $tags];
    }

    /**
     * @Route("/popular", name="oktothek_tag_popular")
     * @Method("GET")
     * @Template()
     */
    public function popularTagsAction()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Tag');
        $tags = $repo->findPopularTags();

        return ['tags' => $tags];
    }

    /**
     * @Route("/new", name="oktothek_tag_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(new TagType(), $tag);
        $form->add('submit', 'submit', ['label' => 'oktothek.tag_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($tag);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_tag');

                return $this->redirect($this->generateUrl('oktothek_tag_index'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_tag');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/edit/popular", name="oktothek_tag_edit_popular")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editPopularTagsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tagCollection = new TagCollection();
        $tagCollection->setTags($em->getRepository('AppBundle:Tag')->findPopularTags());
        $form = $this->createForm(new TagCollectionType(), $tagCollection);
        $form->add('submit', 'submit', ['label' => 'oktothek.tag_popular_update_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $old_tags = $em->getRepository('AppBundle:Tag')->getPopularTags();
                foreach ($old_tags as $tags) {
                    $tag->setHighlight(false);
                    $em->persist($tag);
                }
                $tags = $form->getData('tags');
                foreach ($tags as $tag) {
                    $tag->setHighlight(true);
                    $em->persist($tag);
                }
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_update_popular_tags');
                return $this->redirect($this->generateUrl('oktothek_tag_popular'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_update_popular_tags');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/edit/{slug}", name="oktothek_tag_edit")
     * @Method({"GET", "PUT"})
     * @Template()
     */
    public function editTagAction(Request $request, Tag $tag)
    {
        # code...
    }
}

?>
