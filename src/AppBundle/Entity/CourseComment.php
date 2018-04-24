<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;
use Bprs\UserBundle\Entity;
use AppBundle\Entity\Comment as BaseComment;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\CourseCommentRepository")
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 */
class CourseComment extends BaseComment {

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Course\Course", inversedBy="comments")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    protected $course;

    public function getCommentedObject() {
        return $this->course;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setCourse($course = null)
    {
        $this->course = $course;
        return $this;
    }
}
