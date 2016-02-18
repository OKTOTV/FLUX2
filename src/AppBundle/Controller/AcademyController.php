<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Course\Coursetype;

/**
 * Academy controller.
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
        $em = $this->getDoctrine()->getManager();
        $coursetypes = $em->getRepository('AppBundle:Course\Coursetype')->findAll(['isActive' => true]);

        return ['coursetypes' => $coursetypes];
    }

    /**
     * @Route("/{coursetype}")
     * @Template()
     */
    public function showCoursetypeAction(Coursetype $coursetype)
    {
        return ['coursetype' => $coursetype];
    }

    /**
     * @Route("/course/{course}")
     * @Template()
     */
    public function showCourseAction(Course $course)
    {
        return ['course' => $course];
    }
}
