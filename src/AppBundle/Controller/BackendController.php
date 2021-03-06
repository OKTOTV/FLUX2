<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * @Route("/backend")
 * @Security("has_role('ROLE_USER')")
 */
class BackendController extends Controller
{
    /**
     * @Route("/", name="backend")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return [];
    }

    /**
     * @Route("/keychains", name="keychains")
     * @Template()
     */
    public function keychainAction(Request $request)
    {
        return [];
    }
}
