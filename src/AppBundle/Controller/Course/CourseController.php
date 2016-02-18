<?php

namespace AppBundle\Controller\Course;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Course\Course;
use AppBundle\Entity\Course\Coursetype;
use AppBundle\Entity\Course\Coursedate;
use AppBundle\Entity\Course\Attendee;

/**
 * @Route("/academy")
 */
class CourseController extends Controller
{
    // /**
    //  * @Route("/", name="oktothek_academy")
    //  * @Template()
    //  */
    // public function indexAction()
    // {
    //     $em = $this->getDoctrine()->getManager();
    //     $coursetypes = $em->getRepository('AppBundle:Course\Coursetype')->findAll(['isActive' => true]);
    //
    //     return ['coursetypes' => $coursetypes];
    // }
    //
    // /**
    //  * @Route("/{coursetype}", name="oktothek_show_coursetype")
    //  */
    // public function showCoursetypeAction(Coursetype $coursetype)
    // {
    //
    // }
    //
    // /**
    //  * @Route("/")
    //  */
    // public function showCourseAction(Course $course)
    // {
    //     # code...
    // }
}
