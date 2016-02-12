<?php

namespace AppBundle\Controller;

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
 * @Route("/backend/course")
 */
class CourseController extends Controller
{
    /**
     * @Route("/", name="oktothek_backend_courses")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $coursetypes = $em->getRepository('AppBundle:Course/Coursetype')->findAll();

        return ['coursetypes' => $coursetypes];
    }

    /**
     * @Route("/coursetype/new", name="oktothek_backend_new_coursetype")
     * @Template()
     */
    public function newCoursetypeAction(Request $request)
    {
        $coursetype = new Coursetype();
        $form = $this->createForm(new CoursetypeType(), $coursetype);
        $form->add('submit', 'submit', ['label' => 'oktothek.coursetype_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($coursetype);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_coursetype');

                return $this->redirect($this->generateUrl('oktothek_backend_courses'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_coursetype');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/coursetype/{coursetype}/show", name="oktothek_backend_show_coursetype")
     * @Template()
     */
    public function showCoursetypeAction(Coursetype $coursetype)
    {
        return ['coursetype' => $coursetype];
    }

    /**
     * @Route("course/{course}/show", name="oktothek_backend_show_course")
     * @Template()
     */
    public function showCourseAction(Course $course)
    {
        return ['course' => $course]
    }

    /**
     * @Route("attendee/{attendee}/show", name="oktothek_backend_show_attendee")
     * @Template()
     */
    public function showAttendeeAction(Attendee $attendee)
    {
        return ['attendee' => $attendee];
    }
}
