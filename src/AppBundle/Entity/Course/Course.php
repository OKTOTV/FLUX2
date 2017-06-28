<?php

namespace AppBundle\Entity\Course;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Course
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\CourseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Course
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
     *
     * @ORM\ManyToOne(targetEntity="Coursetype", inversedBy="courses")
     * @ORM\JoinColumn(name="coursetype_id", referencedColumnName="id")
     */
    private $coursetype;

    /**
     * @Assert\Length(max=255, maxMessage="oktothek.backend_course_trainer_maxLength")
     * @ORM\Column(name="trainer", type="string", length=255, nullable=true)
     */
    private $trainer;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Attendee", inversedBy="courses")
     * @ORM\JoinTable(name="courses_attendees")
     */
    private $attendees;

    /**
     * @Assert\GreaterThan(value=0, message="oktothek.backend_course_attendees_greater_than")
     * @ORM\Column(type="smallint", name="max_attendees")
     */
    private $max_attendees;

    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity="Coursedate", mappedBy="course", cascade={"persist", "remove"})
     * @ORM\OrderBy({"courseStart" = "ASC"})
     */
    private $dates;

    /**
     * @ORM\Column(name="deadline", type="datetime", nullable=true)
     */
    private $deadline;

    /**
     * @ORM\Column(name="is_active", type="boolean", options={"default"=false})
     */
    private $is_active;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CourseComment", mappedBy="course")
     */
    private $comments;

    public function __construct() {
        $this->dates = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        $label = "";
        $count = count($this->dates);
        for ($i=0; $i < $count; $i++) {
            if ($i == ($count-1)) { //last
                $label = $label.$this->dates[$i]->getCourseStart()->format('d.m.Y').' '.
                $this->dates[$i]->getCourseStart()->format('H:i').' - '.
                $this->dates[$i]->getCourseEnd()->format('H:i');
            } else {
                $label = $label.$this->dates[$i]->getCourseStart()->format('d.m.Y').' '.
                $this->dates[$i]->getCourseStart()->format('H:i').' - '.
                $this->dates[$i]->getCourseEnd()->format('H:i').' / ';
            }
        }
        return $label;
    }

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

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
     * Set coursetype
     *
     * @param \stdClass $coursetype
     * @return Course
     */
    public function setCoursetype($coursetype)
    {
        $this->coursetype = $coursetype;

        return $this;
    }

    /**
     * Get coursetype
     *
     * @return \stdClass
     */
    public function getCoursetype()
    {
        return $this->coursetype;
    }

    /**
     * Set trainer
     *
     * @param \stdClass $trainer
     * @return Course
     */
    public function setTrainer($trainer)
    {
        $this->trainer = $trainer;

        return $this;
    }

    /**
     * Get trainer
     *
     * @return \stdClass
     */
    public function getTrainer()
    {
        return $this->trainer;
    }

    /**
     * Set attendees
     *
     * @param \stdClass $attendees
     * @return Course
     */
    public function setAttendees($attendees)
    {
        $this->attendees = $attendees;

        return $this;
    }

    /**
     * Get attendees
     *
     * @return \stdClass
     */
    public function getAttendees()
    {
        return $this->attendees;
    }

    /**
     * Set max_attendees
     *
     * @param integer $maxAttendees
     * @return Course
     */
    public function setMaxAttendees($maxAttendees)
    {
        $this->max_attendees = $maxAttendees;

        return $this;
    }

    /**
     * Get max_attendees
     *
     * @return integer
     */
    public function getMaxAttendees()
    {
        return $this->max_attendees;
    }

    /**
     * Add attendees
     *
     * @param \AppBundle\Entity\Attendee $attendees
     * @return Course
     */
    public function addAttendee($attendees)
    {
        $this->attendees[] = $attendees;
        $attendees->addCourse($this);
        return $this;
    }

    /**
     * Remove attendees
     *
     * @param \AppBundle\Entity\Attendee $attendees
     */
    public function removeAttendee($attendee)
    {
        $this->attendees->removeElement($attendee);
        $attendee->removeCourse($this);
        return $this;
    }

    /**
     * Add dates
     *
     * @param \AppBundle\Entity\Coursedate $dates
     * @return Course
     */
    public function addDate($dates)
    {
        $this->dates[] = $dates;
        $dates->setCourse($this);
        return $this;
    }

    /**
     * Remove dates
     *
     * @param \AppBundle\Entity\Coursedate $dates
     */
    public function removeDate($dates)
    {
        $dates->setCourse();
        $this->dates->removeElement($dates);
    }

    /**
     * Get dates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDates()
    {
        return $this->dates;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Course
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    public function isFull()
    {
        if (count($this->attendees) >= $this->max_attendees) {
            return true;
        }
        return false;
    }

    public function getDeadline()
    {
        return $this->deadline;
    }

    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
        return $this;
    }
}
