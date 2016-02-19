<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Course\Coursetype;
use AppBundle\Entity\Course\Course;
use AppBundle\Form\Course\AttendeeType;
use AppBundle\Entity\Course\Attendee;
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
     * @Route("/{coursetype}", name="oktothek_academy_coursetype")
     * @Template()
     */
    public function showCoursetypeAction(Coursetype $coursetype)
    {
        return ['coursetype' => $coursetype];
    }

    /**
     * @Route("/book/{course}", name="oktothek_academy_book_course")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function bookCourseAction(Request $request, Course $course)
    {
        $attendee = new Attendee();
        $course->addAttendee($attendee);

        $form = $this->createForm(new AttendeeType(), $attendee);
        $form->add('submit', 'submit', ['label' => 'oktothek.attendee_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($attendee);
                $em->persist($course);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_book_course');
                // TODO: send mail etc,
                return $this->redirect($this->generateUrl('oktothek_academy_coursetype', ['coursetype' => $course->getCoursetype()->getId()]));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_book_course');
            }
        }

        return ['form' => $form->createView(), 'course' => $course];
    }
}
