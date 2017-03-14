<?php

namespace AppBundle\Model;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AcademyService
{
    const ACADEMY_OPEN_TRANSACTION =   'oktothek_academy_open_transaction'; // opened an transaction. user is on SOFORT page to enter online banking credentials
    const ACADEMY_CLOSED_TRANSACTION = 'oktothek_academy_closed_transaction'; // user successfully finished the transaction
    const ACADEMY_REFUND_TRANSACTION = 'oktothek_academy_refund_transaction'; // opened successfully a refund at SOFORT
    const ACADEMY_MONEY =              'oktothek_academy_money_open'; // user wants to pay with real money (urgh) at okto
    const ACADEMY_MONEY_CLOSED =       'oktothek_academy_money_closed'; // user successfully paid with real money

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

    public function bookCourse($attendee, $course, $sendMail = true, $status = AcademyService::ACADEMY_MONEY)
    {
        $em = $this->em;
        $attendee->setPaymentStatus($status);
        $em->persist($attendee);
        $em->persist($course);
        $em->flush();
        if ($sendMail) {
            $this->sendBookingSuccessMail($attendee);
        }
    }

    public function attendeePayedWithMoney($attendee)
    {
        $em = $this->em;
        $attendee->setPaymentStatus(AcademyService::ACADEMY_MONEY_CLOSED);
        $em->persist($attendee);
        $em->flush();
        $this->sendPayedMoneySuccessMail($attendee);
    }

    public function registerCourse($attendee, $course, $sendMail = true)
    {
        $em = $this->em;
        $em->persist($attendee);
        $em->persist($course);
        $em->flush();
        if ($sendMail) {
            $this->sendRegisterSuccessMail($attendee);
        }
    }

    public function completedPayment($attendee)
    {
        $em = $this->em;
        $attendee->setPaymentStatus(AcademyService::ACADEMY_CLOSED_TRANSACTION);
        $em->persist($attendee);
        $em->flush();
        $this->sendBookingSuccessMail($attendee);
    }

    /**
     * Use this function if you want to refund a single attenddee transactions
     */
    public function refundAttendeeCourse($attendee, $course, $bic, $iban)
    {
        $refund = $this->sofort->getRefund($attendee, $bic, $iban);

        if ($attendee->getReducedEligible()) {
            $refund->addRefund(
                $attendee->getTransactionId(),
                $course->getCoursetype()->getPriceReduced()
            );
        } else {
            $refund->addRefund(
                $attendee->getTransactionId(),
                $course->getCoursetype()->getPrice()
            );
        }

        $refund->sendRequest();
        if ($refund->isError()) {
            return false;
        }

        $attendee->setPaymentStatus(AcademyService::ACADEMY_REFUND_TRANSACTION);
        $this->em->persist($attendee);
        $this->em->flush();

        return true;
    }

    /**
     * cancel a complete course. You'll have to handle each Attendee separately
     */
    public function cancelCourse($course)
    {
        $course->setIsActive(false);
        $this->em->persist($course);
        $this->em->flush();
        if (count($course->getAttendees())) {
            foreach ($course->getAttendees() as $attendee) {
                # TODO: send mail what to do: new course, refund, etc.
            }
        }

    }

    /**
     * move Attendee to another course.
     * sends email with information again.
     */
    public function moveAttendeeFromCourseToCourse($attendee, $fromCourse, $toCourse)
    {
        $fromCourse->removeAttendee($attendee);
        $toCourse->addAttendee($attendee);
        $this->em->persist($fromCourse);
        $this->em->persist($toCourse);
        $this->em->persist($attendee);
        $this->em->flush();
        $this->sendMovedAttendeeMail($attendee, $fromCourse, $toCourse);
        return true;
    }

    public function getAvailableCoursesToMove() {
        return $this->em->getRepository('AppBundle:Course\Course')
            ->findFutureCourses();
    }

    private function sendMovedAttendeeMail($attendee, $fromCourse, $toCourse) {
        //TODO: add mailer and send mail
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
