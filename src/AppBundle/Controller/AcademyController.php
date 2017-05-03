<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Course\Coursetype;
use AppBundle\Entity\Course\Course;
use AppBundle\Form\Course\AttendeeType;
use AppBundle\Entity\Course\Attendee;

use AppBundle\Entity\Course\Coursepackage;
use AppBundle\Entity\Course\CoursepackageSelect;
use AppBundle\Form\Course\CoursepackageSelectType;

/**
 * Academy controller.
 * @Route("/workshops")
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
        $coursetypes = $em->getRepository('AppBundle:Course\Coursetype')->findActiveCoursetypes();
        $highlights = $em->getRepository('AppBundle:Course\Coursetype')->findHighlightedCoursetypes();
        $coursepackages = $em->getRepository('AppBundle:Course\Coursepackage')->findActiveCoursepackages();

        return [
            'coursetypes' => $coursetypes,
            'highlights' => $highlights,
            'coursepackages' => $coursepackages
        ];
    }

    /**
     * @Route("/{coursetype}", name="oktothek_academy_coursetype")
     * @Template()
     */
    public function showCoursetypeAction($coursetype)
    {
        $em = $this->getDoctrine()->getManager();
        $coursetype = $em->getRepository('AppBundle:Course\Coursetype')
            ->findActiveCoursetype($coursetype);
        if ($coursetype) {
            return ['coursetype' => $coursetype];
        }
        throw $this->createNotFoundException();
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
            $form->add(
                'register',
                'submit',
                [
                    'label' => 'oktothek.register_attendee_button',
                    'attr' => ['class' => 'btn btn-primary']
                ]
            );
        } else {
            $form->add(
                'sofort',
                'submit',
                [
                    'label' => 'oktothek.book_sofort_button',
                    'attr' => ['class' => 'btn btn-primary']
                ]
            );
        }
        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($form->has('sofort') && $form->get('sofort')->isClicked()) {
                    if ($url = $this->get('oktothek_academy')->bookCourseSOFORT(
                        $attendee,
                        $course
                    )) {
                        return $this->redirect($url);
                    }
                    $this->get('session')->getFlashBag()
                        ->add('error', 'oktothek.error_book_course');
                } elseif ($form->has('register') && $form->get('register')->isClicked()) {
                    $this->get('oktothek_academy')->registerCourse(
                        $attendee,
                        $course
                    );
                    $this->get('session')->getFlashBag()->add(
                        'success',
                        'oktothek.success_register_course'
                    );
                    return $this->redirect(
                        $this->generateUrl(
                            'oktothek_academy_coursetype',
                            ['coursetype' => $course->getCoursetype()->getId()]
                        )
                    );
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
     * @TODO add slug to coursepackage
     * @Route("/coursepackage/{slug}", name="oktothek_academy_show_coursepackage")
     * @Template()
     */
    public function showCoursepackageAction(Coursepackage $coursepackage)
    {
        return ['coursepackage' => $coursepackage];
    }

    /**
     * @Route("/coursepackage/{coursepackage}/book", name="oktothek_academy_book_coursepackage")
     * @Template()
     */
    public function bookCoursepackageAction(Request $request, Coursepackage $coursepackage)
    {
        $form = $this->get('oktothek_coursepackage')->getFormForCoursepackage($coursepackage);
        if ($form) { // can create coursepackage form. (courses are available)
            if ($request->getMethod() == "POST") { //sends form
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $coursepackageSelection = $this->get('oktothek_coursepackage')->getCoursepackageSelection($form);
                    if ($form->has('sofort') && $form->get('sofort')->isClicked()) {
                        if ($url = $this->get('oktothek_course')->bookCoursepackageSOFORT($attendee, $course)) {
                            return $this->redirect($url);
                        }
                        $this->get('session')->getFlashBag()->add('error', 'oktothek.error_sofort_book_coursepackage');

                    } elseif ($form->has('register') && $form->get('register')->isClicked()) {
                        $this->get('oktothek_coursepackage')->registerCourses($coursepackageSelection);
                        $this->get('session')->getFlashBag()->add('success', 'oktothek.success_register_coursepackage');
                        return $this->redirect($this->generateUrl('oktothek_academy_coursetype', ['coursetype' => $course->getCoursetype()->getId()]));
                    }
                } else {
                    $this->get('session')->getFlashBag()->add('error', 'oktothek.error_book_coursepackage');
                }
            }

            return ['form' => $form->createView()];
        }
        $this->get('session')->getFlashBag()->add('info', 'oktothek.info_book_coursepackage_no_free_courses');
        return $this->redirect($this->generateUrl('oktothek_academy'));
    }

    /**
    * @Route("/book/{uniqID}/coursepackage/complete", name="oktothek_academy_complete_book_coursepackage")
    */
    public function completeBookCoursepackageAction($uniqID)
    {
        $em = $this->getDoctrine()->getManager();
        $attendee = $em->getRepository('AppBundle:Course\Attendee')->findOneBy(['uniqID' => $uniqID]);
        $this->get('oktothek_coursepackage')->completedPayment($attendee);
        $this->get('session')->getFlashBag()->add('success', 'oktothek.success_book_SOFORT_coursepackage');
        return $this->redirect($this->generateUrl('oktothek_academy'));
    }
}
