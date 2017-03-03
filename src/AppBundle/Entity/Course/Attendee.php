<?php

namespace AppBundle\Entity\Course;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Attendee
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AttendeeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Attendee
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=250)
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * family name
     * @Assert\NotBlank()
     * @Assert\Length(max=250)
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=250)
     * @Assert\Email(checkMX = true)
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     * @Assert\Length(max=250)
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @var boolean
     * pays less
     * @ORM\Column(name="reduced_eligible", type="boolean")
     */
    private $reducedEligible;

    /**
     * @ORM\ManyToMany(targetEntity="Course", mappedBy="attendees")
     */
    private $courses;

    /**
     * @ORM\Column(name="transactionId", type="string", length=255, nullable=true)
     */
    private $transactionId;

    /**
     * @ORM\Column(name="paymentStatus", type="string", length=255, nullable=true)
     */
    private $paymentStatus;

    /**
     * @ORM\Column(name="uniqID", type="string", length=255, nullable=true)
     */
    private $uniqID;

    /**
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    public function __toString()
    {
        return $this->name.' '.$this->surname;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Attendee
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Attendee
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Attendee
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Attendee
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set reducedEligible
     *
     * @param boolean $reducedEligible
     * @return Attendee
     */
    public function setReducedEligible($reducedEligible)
    {
        $this->reducedEligible = $reducedEligible;

        return $this;
    }

    /**
     * Get reducedEligible
     *
     * @return boolean
     */
    public function getReducedEligible()
    {
        return $this->reducedEligible;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uniqID = uniqID();
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Add courses
     *
     * @param \AppBundle\Entity\Course $courses
     * @return Attendee
     */
    public function addCourse($courses)
    {
        $this->courses[] = $courses;

        return $this;
    }

    /**
     * Remove courses
     *
     * @param \AppBundle\Entity\Course $courses
     */
    public function removeCourse($courses)
    {
        $this->courses->removeElement($courses);
    }

    /**
     * Get courses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Set transactionId
     *
     * @param string $transactionId
     * @return Attendee
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set paymentStatus
     *
     * @param string $paymentStatus
     * @return Attendee
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * Get paymentStatus
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * Set uniqID
     *
     * @param string $uniqID
     * @return Attendee
     */
    public function setUniqID($uniqID)
    {
        $this->uniqID = $uniqID;

        return $this;
    }

    /**
     * Get uniqID
     *
     * @return string
     */
    public function getUniqID()
    {
        return $this->uniqID;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAt($datetime = null)
    {
        if (!$datetime) {
            $datetime = new \DateTime();
        }
        $this->updatedAt = $datetime;
        return $this;
    }
}
