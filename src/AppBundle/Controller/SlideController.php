<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/slider")
 */
class SlideController extends Controller
{
    /**
    * @Route("/{number}.{_format}", name="slider", requirements={"number": "\d+", "_format": "html|json"}, defaults={"number": 5, "_format": "html"})
    * @Template()
    */
    public function sliderAction($number)
    {
        $em = $this->getDoctrine()->getManager();
        $slides = $em->getRepository('AppBundle:Slide')->findNewestSlides($number);

        return ['slides' => $slides];
    }
}
