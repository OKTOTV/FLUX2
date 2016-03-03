<?php

namespace AppBundle\Model;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AcademyService
{
    const ACADEMY_OPEN_TRANSACTION =   'oktothek_academy_open_transaction';
    const ACADEMY_CLOSED_TRANSACTION = 'oktothek_academy_closed_transaction';
    const ACADEMY_MONEY =              'oktothek_academy_money_open';
    const ACADEMY_MONEY_CLOSED =       'oktothek_academy_money_closed';

    private $sofort;
    private $em;
    private $router;

    public function __construct($sofort, $entity_manager, $router)
    {
        $this->sofort = $sofort;
        $this->em = $entity_manager;
        $this->router = $router;
    }

    public function bookCourseSOFORT($attendee, $course)
    {
        $Sofortueberweisung = null;
        if ($attendee->getReducedEligible()) {
            $Sofortueberweisung = $this->sofort->getSofortueberweisung($course->getCoursetype()->getPriceReduced());
        } else {
            $Sofortueberweisung = $this->sofort->getSofortueberweisung($course->getCoursetype()->getPrice());
        }
        $Sofortueberweisung->setSuccessUrl(
            $this->router->generate('oktothek_academy_complete_book_course', ['uniqID' => $attendee->getUniqID()], UrlGeneratorInterface::ABSOLUTE_URL),
            true
        );
        $Sofortueberweisung->setAbortUrl($this->router->generate('oktothek_academy',[], UrlGeneratorInterface::ABSOLUTE_URL));
        $Sofortueberweisung->setReason('Testueberweisung');
        if ($this->sofort->startTransaction($Sofortueberweisung)) {
            $attendee->setTransactionId($Sofortueberweisung->getTransactionId());
            $attendee->setPaymentStatus(AcademyService::ACADEMY_OPEN_TRANSACTION);
            $this->em->persist($attendee);
            $this->em->persist($course);
            $this->em->flush();
            return $Sofortueberweisung->getPaymentUrl();
            // everything went as expected. Redirect user to SOFORT now to complete the payment
        }
        // SOFORT transaction can't be started
        return false;
    }

    public function bookCourse($attendee, $course)
    {
        $em = $this->em;
        $attendee->setPaymentStatus(AcademyService::ACADEMY_MONEY);
        $em->persist($attendee);
        $em->persist($course);
        $em->flush();
        $this->sendBookingSuccessMail($attendee);
    }

    public function attendeePayedWithMoney($attendee)
    {
        $em = $this->em;
        $attendee->setPaymentStatus(AcademyService::ACADEMY_MONEY_CLOSED);
        $em->persist($attendee);
        $em->flush();
        $this->sendPayedMoneySuccessMail($attendee);
    }

    public function registerCourse($attendee, $course)
    {
        $em = $this->em;
        $em->persist($attendee);
        $em->persist($course);
        $em->flush();
        $this->sendRegisterSuccessMail($attendee);
    }

    public function completedPayment($attendee)
    {
        $em = $this->em;
        $attendee->setPaymentStatus(AcademyService::ACADEMY_CLOSED_TRANSACTION);
        $em->persist($attendee);
        $em->flush();
        $this->sendBookingSuccessMail($attendee);
    }

    private function sendBookingSuccessMail($attendee)
    {
        //TODO: add mailer and send a mail
    }

    private function sendRegisterSuccessMail($attendee)
    {
        //TODO: add mailer and send a mail
    }

    private function sendPayedMoneySuccessMail($attendee)
    {
        //TODO: add mailder and send a mail
    }
}
