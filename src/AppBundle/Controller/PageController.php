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
     * @Route("/{page}", name="oktothek_page_show")
     * @Template()
     */
    public function showAction($page)
    {
        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page')->findOneBy(['slug' => $page]);
        return ['page' => $page];
    }
}
