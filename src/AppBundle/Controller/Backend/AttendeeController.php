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
     * @Route("/{attendee}/create", name="oktothek_backend_create_attendee")
     * @Template()
     */
    public function createAction(Request $request, Attendee $attendee)
    {
        // TODO: create attendee (frontoffice help for cash payment)
        // maybe better with role in frontend??
    }

    /**
     * @Route("/{attendee}/edit", name="oktothek_backend_edit_attendee")
     * @Template()
     */
    public function editAction(Request $request, Attendee $attendee)
    {
        // TODO: edit attendee defaultinfo.
    }

    /**
     * @Route("/{attendee}/paymentstatus", name="oktothek_backend_edit_paymentstatus")
     * @Template()
     */
    public function setPaymentstatusAction(Request $request, Attendee $attendee)
    {
        // TODO: update attendee paymentstatus (frontoffice form to update paymentsettings)
    }
}
