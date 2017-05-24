<?php

namespace AppBundle\Entity\Course;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Intl\Intl;

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

    /**
     * @var string
     * @Assert\Length(max=500)
     * @ORM\Column(name="info", type="string", length=500, nullable=true)
     */
    private $info;

    /**
     * @var boolean
     * if the attendee was present at the course or not.
     * @ORM\Column(name="present", type="boolean", options={"default" = false}, nullable=true)
     */
    private $present;

    /**
     * @ORM\Column(name="adress", type="string", length=200, nullable=true)
     * @Assert\Length(max=200)
     * @Assert\NotBlank()
     */
    private $adress;

    /**
     * @ORM\Column(name="zipcode", type="string", length=20, nullable=true)
     * @Assert\Length(max=20)
     * @Assert\NotBlank()
     */
    private $zipcode;

    /**
     * @ORM\Column(name="city", type="string", length=120, nullable=true)
     * @Assert\Length(max=120)
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @ORM\Column(name="country", type="string", length=20, nullable=true)
     * @Assert\Length(max=20)
     * @Assert\NotBlank()
     */
    private $country;

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
        $this->present = false;
    }

    /**
     * Add courses
     *
     * @param \AppBundle\Entity\Course $courses
     * @return Attendee
     */
    public function addCourse($course)
    {
        $this->courses[] = $course;
        return $this;
    }

    /**
     * Remove courses
     *
     * @param \AppBundle\Entity\Course $course
     */
    public function removeCourse($course)
    {
        $this->courses->removeElement($course);
        return $this;
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
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function setInfo($info)
    {
        $this->info = $info;
        return $this;
    }

    public function getPresent()
    {
        return $this->present;
    }

    public function isPresent()
    {
        return $this->present;
    }

    public function wasPresent()
    {
        return $this->present;
    }

    public function setPresent($present)
    {
        $this->present = $present;
        return $this;
    }

    public function getAdress()
    {
        return $this->adress;
    }

    public function setAdress($adress)
    {
        $this->adress = $adress;
        return $this;
    }

    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    public function getZipcode()
    {
        return $this->zipcode;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

     public function getCountryname()
    {
        return Intl::getRegionBundle()->getCountryName($this->getCountry());
    }
}
