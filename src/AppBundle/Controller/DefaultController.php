<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
    * @Route("/slider", name="slider")
    * @Template()
    */
    public function sliderAction()
    {
        $em = $this->getDoctrine()->getManager();
        $episodePins = $em->getRepository('AppBundle:EpisodePin')->findBy(array(), array('onlineAt' => 'ASC'), 5, 0);

        return array('slides' => $episodePins);
    }
}
