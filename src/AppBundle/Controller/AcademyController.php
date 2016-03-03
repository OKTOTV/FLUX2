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
        if ($course->getCoursetype()->getPrice() <= 0) {
            $form->add('register', 'submit', ['label' => 'oktothek.register_attendee_button', 'attr' => ['class' => 'btn btn-primary']]);
        } else {
            $form->add('sofort', 'submit', ['label' => 'oktothek.book_sofort_button', 'attr' => ['class' => 'btn btn-primary']]);
            $form->add('submit', 'submit', ['label' => 'oktothek.attendee_create_button', 'attr' => ['class' => 'btn btn-default']]);
        }
        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                // die(var_dump($form->getClickedButton()));
                if ($form->has('sofort') && $form->get('sofort')->isClicked()) {
                    if ($url = $this->get('oktothek_academy')->bookCourseSOFORT($attendee, $course)) {
                        return $this->redirect($url);
                    }
                    $this->get('session')->getFlashBag()->add('error', 'oktothek.error_sofort_book_course');
                } elseif ($form->has('register') && $form->get('register')->isClicked()) {
                    $this->get('oktothek_academy')->registerCourse($attendee, $course);
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_register_course');
                    return $this->redirect($this->generateUrl('oktothek_academy_coursetype', ['coursetype' => $course->getCoursetype()->getId()]));
                } else {
                    $this->get('oktothek_academy')->bookCourse($attendee, $course);
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_book_course');
                    return $this->redirect($this->generateUrl('oktothek_academy_coursetype', ['coursetype' => $course->getCoursetype()->getId()]));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_book_course');
            }
        }

        return ['form' => $form->createView(), 'course' => $course];
    }

    /**
    * @Route("/book/{uniqID}/complete", name="oktothek_academy_complete_book_course")
    */
    public function completeBookCourseAction($uniqID)
    {
        $em = $this->getDoctrine()->getManager();
        $attendee = $em->getRepository('AppBundle:Course\Attendee')->findOneBy(['uniqID' => $uniqID]);
        $this->get('oktothek_academy')->completedPayment($attendee);
        $this->get('session')->getFlashBag()->add('success', 'oktothek.success_book_SOFORT_course');
        return $this->redirect($this->generateUrl('oktothek_academy'));
    }

    /**
    * @Route("/book/{transactionId}/cancel", name="oktothek_academy_cancel_book_course")
    */
    public function cancelBookedCourseAction($transactionId)
    {
        # code...
    }
}
