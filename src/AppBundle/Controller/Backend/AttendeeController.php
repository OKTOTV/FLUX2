<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Course\Course;
use AppBundle\Entity\Course\Attendee;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\Backend\MoveAttendeeType;
use AppBundle\Form\Course\AttendeeType;

/**
 * @Route("/backend/attendee")
 */
class AttendeeController extends Controller
{
    /**
     * @Route("s/", name="oktothek_backend_attendee_index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Course\Attendee')->findAllAttendees(0, true);
        $paginator = $this->get('knp_paginator');
        $attendees = $paginator->paginate($query, $request->query->get('page', 1), $request->query->get('results', 10));

        return ['attendees' => $attendees];
    }

    /**
     * @Route("/{attendee}/show", name="oktothek_backend_show_attendee")
     * @Template()
     */
    public function showAction(Attendee $attendee)
    {
        return ['attendee' => $attendee];
    }

    /**
     * @Route("/{course}/create", name="oktothek_backend_create_attendee")
     * @Template()
     */
    public function createAction(Request $request, Course $course)
    {
        $attendee = new Attendee();
        $course->addAttendee($attendee);

        $form = $this->createForm(AttendeeType::class, $attendee);
        if ($course->getCoursetype()->getPrice() <= 0) {
            $form->add(
                'register',
                SubmitType::class,
                [
                    'label' => 'oktothek.register_attendee_button',
                    'attr' => ['class' => 'btn btn-primary']
                ]
            );
        } else {
            $form->add(
                'money',
                SubmitType::class,
                [
                    'label' => 'oktothek.attendee_create_button',
                    'attr' => ['class' => 'btn btn-primary']
                ]
            );
        }
        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($form->has('register') && $form->get('register')->isClicked()) {
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
            } elseif ($form->has('money') && $form->get('money')->isClicked()) {
                $this->get('oktothek_academy')->bookCourse(
                    $attendee,
                    $course,
                    false,
                    AcademyService::ACADEMY_MONEY_CLOSED
                );
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_money_register_course');
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_book_course');
            }
        }

        return ['form' => $form->createView(), 'course' => $course];
    }

    /**
     * @Route("/{attendee}/edit", name="oktothek_backend_edit_attendee")
     * @Template()
     */
    public function editAction(Request $request, Attendee $attendee)
    {
        $form = $this->createForm(AttendeeType::class, $attendee);
        $form->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'oktothek_edit_attendee_button',
                'attr' => ['class' => 'btn btn-primary']
            ]
        );
        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($attendee);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'oktothek.success_edit_attendee'
                );
                return $this->redirect(
                    $this->generateUrl(
                        'oktothek_backend_show_attendee',
                        ['attendee' => $attendee->getId()]
                    )
                );
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_attendee');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{attendee}/paymentstatus", name="oktothek_backend_edit_paymentstatus")
     * @Template()
     */
    public function setPaymentstatusAction(Request $request, Attendee $attendee)
    {
        // TODO: update attendee paymentstatus (frontoffice form to update paymentsettings)
    }

    /**
     * @Route("/{attendee}/move", name="oktothek_backend_move_attendee")
     * @Template()
     */
    public function moveAttendeeAction(Request $request, Attendee $attendee)
    {
        $openCourses = $this->get('oktothek_academy')
            ->getAvailableCoursesToMove($attendee);

        $form = $this->createForm(
            MoveAttendeeType::class,
            $attendee,
            [
                'fromCourses' => $attendee->getCourses(),
                'toCourses' => $openCourses,
                'translator' => $this->get('translator')
            ]
        );

        $form->add('submit', SubmitType::class, [
            'label' => 'oktothek.attendee_move_button',
            'attr' => ['class' => 'btn btn-primary']
        ]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->get('oktothek_academy')->moveAttendeeFromCourseToCourse(
                    $attendee,
                    $form->get('fromCourse')->getData(),
                    $form->get('toCourse')->getData()
                );
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'oktothek.success_move_attendee'
                );
                return $this->redirect(
                    $this->generateUrl(
                        'oktothek_backend_show_attendee',
                        ['attendee' => $attendee->getId()]
                    )
                );
            } else {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    'oktothek.error_move_attendee'
                );
            }
        }

        return ['form' => $form->createView(), 'attendee' => $attendee];
    }
}
