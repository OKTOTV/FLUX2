<?php

namespace AppBundle\Model;

use AppBundle\Form\Course\AttendeeType;
use AppBundle\Form\Course\CoursepackageRadioType;
use AppBundle\Entity\Course\CoursepackageSelect;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CoursepackageService
{
    const COURSEPACKAGE_CLOSED_TRANSACTION = 'oktothek_coursepackage_closed_transaction';
    const COURSEPACKAGE_OPEN_TRANSACTION =   'oktothek_coursepackage_open_transaction';
    const COURSEPACKAGE_MONEY_OPEN =         'oktothek_coursepackage_money_open';
    const COURSEPACKAGE_MONEY_CLOSED =       'oktothek_coursepackage_money_closed';

    private $factory;
    private $academy_service;

    public function __construct($formfactory, $academy_service)
    {
        $this->factory = $formfactory;
        $this->academy_service = $academy_service;
    }

    public function getFormForCoursepackage($coursepackage)
    {
        $form = $this->factory->createBuilder();
        $form->add('attendee', AttendeeType::class);
        foreach ($coursepackage->getCoursetypes() as $key => $coursetype) {
            $form->add('courseSelection_'.$key, EntityType::class, [
                'label' => $coursetype->getTitle(),
                'class' => "AppBundle:Course\Course",
                'expanded' => true,
                'multiple' => false,
                'choice_label' => function($course) {
                    return $course;
                },
                'choices' => $coursetype->getCourses()]);
        }
        if ($coursepackage->getPrice() <= 0) {
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

        return $form->getForm();
    }

    public function getCoursepackageSelection($form)
    {
        $data = $form->getData();
        $coursepackageSelect = new coursepackageSelect();
        $coursepackageSelect->setAttendee($data['attendee']);
        unset($data['attendee']);
        foreach ($data as $courseSelection) {
            $coursepackageSelect->addCourse($courseSelection);
        }

        return $coursepackageSelect;
    }

    public function registerCourses($coursepackageSelect)
    {
        foreach($coursepackageSelect->getCourses() as $course)
        {
            $course->addAttendee($coursepackageSelect->getAttendee());
            $this->academy_service->registerCourse(
                $coursepackageSelect->getAttendee(),
                $course,
                false
            );
        }
        $this->sendRegisterSuccessMail($coursepackageSelect->getAttendee());
    }

    public function bookCourses($coursepackageSelect)
    {
        foreach($coursepackageSelect->getCourses() as $course)
        {
            $course->addAttendee($coursepackageSelect->getAttendee());
            $this->academy_service->bookCourse(
                $coursepackageSelect->getAttendee(),
                $course,
                false,
                CoursepackageService::COURSEPACKAGE_MONEY_OPEN
            );
        }
        $this->sendBookingSuccessMail($coursepackageSelect->getAttendee());
    }

    public function bookCoursePackageSOFORT($attendee, $coursepackage)
    {
        $Sofortueberweisung = null;
        if ($attendee->getReducedEligible()) {
            $Sofortueberweisung = $this->sofort->getSofortueberweisung($coursepackage->getPriceReduced());
        } else {
            $Sofortueberweisung = $this->sofort->getSofortueberweisung($coursepackage->getPrice());
        }
        $Sofortueberweisung->setSuccessUrl(
            $this->router->generate(
                'oktothek_academy_complete_book_coursepackage',
                ['uniqID' => $attendee->getUniqID()],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            true
        );
        $Sofortueberweisung->setAbortUrl(
            $this->router->generate(
                'oktothek_academy',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
        $Sofortueberweisung->setReason($coursepackage->getTitle());
        if ($this->sofort->startTransaction($Sofortueberweisung)) {
            $attendee->setTransactionId($Sofortueberweisung->getTransactionId());
            $attendee->setPaymentStatus(CoursepackageService::COURSEPACKAGE_OPEN_TRANSACTION);
            $this->em->persist($attendee);
            $this->em->flush();
            return $Sofortueberweisung->getPaymentUrl();
            // everything went as expected. Redirect user to SOFORT now to complete the payment
        }
        // SOFORT transaction can't be started
        return false;
    }

    public function completedPayment($attendee)
    {
        $em = $this->em;
        $attendee->setPaymentStatus(CoursepackageService::COURSEPACKAGE_CLOSED_TRANSACTION);
        $em->persist($attendee);
        $em->flush();
        $this->sendBookingSuccessMail($attendee);
    }

    public function sendBookingSuccessMail($attendee)
    {
        //TODO: send coursepackage completed booking mail
    }

    public function sendRegisterSuccessMail($attendee)
    {
        //TODO: send coursepackage register success mail
    }
}
