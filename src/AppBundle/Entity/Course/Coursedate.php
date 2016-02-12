<?php

namespace AppBundle\Entity\Course;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coursedate
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Coursedate
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
     * @var \DateTime
     *
     * @ORM\Column(name="course_start", type="datetime")
     */
    private $courseStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="course_end", type="datetime")
     */
    private $courseEnd;

    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="dates")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

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
     * Set courseStart
     *
     * @param \DateTime $courseStart
     * @return Coursedate
     */
    public function setCourseStart($courseStart)
    {
        $this->courseStart = $courseStart;

        return $this;
    }

    /**
     * Get courseStart
     *
     * @return \DateTime
     */
    public function getCourseStart()
    {
        return $this->courseStart;
    }

    /**
     * Set courseEnd
     *
     * @param \DateTime $courseEnd
     * @return Coursedate
     */
    public function setCourseEnd($courseEnd)
    {
        $this->courseEnd = $courseEnd;

        return $this;
    }

    /**
     * Get courseEnd
     *
     * @return \DateTime
     */
    public function getCourseEnd()
    {
        return $this->courseEnd;
    }

    /**
     * Set course
     *
     * @param \AppBundle\Entity\Course $course
     * @return Coursedate
     */
    public function setCourse(\AppBundle\Entity\Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \AppBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }
}
