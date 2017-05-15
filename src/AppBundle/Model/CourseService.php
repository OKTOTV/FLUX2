<?php

namespace AppBundle\Model;

class CourseService {

    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function persistCourse($course) {
        $course->setDeadline($course->getDates()[0]->getCourseStart());
        foreach ($course->getDates() as $date) {
            if ($date->getCourseStart() < $course->getDeadline()) {
                $course->setDeadline($date->getCourseStart());
            }
        }

        $this->em->persist($course);
        $this->em->flush();
    }

    public function updateCourse($course)
    {
        $course->setDeadline($course->getDates()[0]->getCourseStart());
        foreach ($course->getDates() as $date) {
            if ($date->getCourseStart() < $course->getDeadline()) {
                $course->setDeadline($date->getCourseStart());
            }
        }

        $this->em->persist($course);
        $this->em->flush();
    }

    public function deleteCourse($course)
    {
        $this->em->remove($course);
        $this->em->flush();
    }
}
