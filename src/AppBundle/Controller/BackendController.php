<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/backend")
 */
class BackendController extends Controller
{
    /**
     * @Route("/", name="backend")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        // Logic
        return array();
    }
}
