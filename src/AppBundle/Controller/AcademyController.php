<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Academy controller.
 *
 * @Route("/academy")
 */
class AcademyController extends Controller
{
    /**
     * @Route("/", name="oktothek_academy")
     * @Template()
     */
    public function showAction()
    {
        return [];
    }
}
