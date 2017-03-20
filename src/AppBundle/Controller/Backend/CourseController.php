<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Course\Course;
use AppBundle\Entity\Course\Coursetype;
use AppBundle\Entity\Course\Coursedate;
use AppBundle\Entity\Course\Attendee;
use AppBundle\Form\Course\CoursetypeType;
use AppBundle\Form\Course\CourseType as CourseFormType;
use AppBundle\Form\Course\CoursedateType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


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
        $coursetypes = $em->getRepository('AppBundle:Course\Coursetype')->findAll();

        return ['coursetypes' => $coursetypes];
    }

    /**
     * @Route("/coursetype/new", name="oktothek_backend_new_coursetype")
     * @Template()
     */
    public function newCoursetypeAction(Request $request)
    {
        $coursetype = new Coursetype();
        $form = $this->createForm(CoursetypeType::class, $coursetype);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.coursetype_create_button', 'attr' => ['class' => 'btn btn-primary']]);

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
     * @Route("/coursetype/{coursetype}/edit", name="oktothek_backend_edit_coursetype")
     * @Template()
     */
    public function editCoursetypeAction(Request $request, Coursetype $coursetype)
    {
        $form = $this->createForm(new CoursetypeType(), $coursetype);
        $form->add('delete', 'submit', ['label' => 'oktothek.coursetype_delete_button', 'attr' => ['class' => 'btn btn-danger']]);
        $form->add('submit', 'submit', ['label' => 'oktothek.coursetype_edit_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('submit')->isClicked()) {
                    $em->persist($coursetype);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_update_coursetype');
                    return $this->redirect($this->generateUrl('oktothek_backend_show_coursetype', ['coursetype' => $coursetype->getId()]));
                } else { //delete post
                    // TODO: service -> send mail to attendees, etc
                    $em->remove($coursetype);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_coursetype');
                    return $this->redirect($this->generateUrl('oktothek_backend_courses'));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_update_coursetype');
            }
        }

        return ['form' => $form->createView(), 'coursetype' => $coursetype];
    }

    /**
     * @Route("/coursetype/{coursetype}/show/{page}", name="oktothek_backend_show_coursetype", requirements={"page": "\d+"}, defaults={"page":1})
     * @Template()
     */
    public function showCoursetypeAction(Coursetype $coursetype, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $courses = $paginator->paginate($em->getRepository('AppBundle:Course\Course')->findCoursesForCoursetypeQuery($coursetype), $page, 4);
        return ['coursetype' => $coursetype, 'courses' => $courses];
    }

    /**
     * @Route("/course/{coursetype}/new", name="oktothek_backend_new_course")
     * @Template()
     */
    public function newCourseAction(Request $request, Coursetype $coursetype)
    {
        $course = new Course();
        $course->setCoursetype($coursetype);
        $form = $this->createForm(new CourseFormType(), $course);
        $form->add('submit', 'submit', ['label' => 'oktothek.course_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($course);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_course');

                return $this->redirect($this->generateUrl('oktothek_backend_courses'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_course');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/course/{course}/edit", name="oktothek_backend_edit_course")
     * @Template()
     */
    public function editCourseAction(Request $request, Course $course)
    {
        $form = $this->createForm(new CourseFormType(), $course);
        $form->add('delete', 'submit', ['label' => 'oktothek.course_delete_button', 'attr' => ['class' => 'btn btn-danger']]);
        $form->add('submit', 'submit', ['label' => 'oktothek.course_edit_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $old_dates = new ArrayCollection();
            foreach ($course->getDates() as $date) {
                $old_dates->add($date);
            }
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('submit')->isClicked()) {
                    foreach ($old_dates as $date) {
                        if (false === $course->getDates()->contains($date)) {
                            $em->remove($date);
                        }
                    }
                    $em->persist($course);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_course');
                    return $this->redirect($this->generateUrl('oktothek_backend_show_coursetype', ['coursetype' => $course->getCoursetype()->getId()]));
                } else { //delete post
                    // TODO: service -> send mail to attendees, etc
                    $coursetype = $course->getCoursetype();
                    $em->remove($course);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_course');
                    return $this->redirect($this->generateUrl('oktothek_backend_show_coursetype', ['coursetype' => $coursetype->getId()]));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_course');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{course}/show", name="oktothek_backend_show_course")
     * @Template()
     */
    public function showCourseAction(Course $course)
    {
        return ['course' => $course];
    }
}
