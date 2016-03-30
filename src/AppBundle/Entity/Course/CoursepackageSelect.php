<?php

namespace AppBundle\Entity\Course;

class CoursepackageSelect
{
    private $attendee;
    private $courses;
    private $coursetypes;
    private $coursepackageRadioSelections;

    public function __construct()
    {
        $this->coursetypes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->coursepackageRadioSelections = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getCoursepackageRadioSelections()
    {
        return $this->coursepackageRadioSelections;
    }

    public function setCoursepackageRadioSelections($selections)
    {
        $this->coursepackageRadioSelections = $selections;
        return $this;
    }

    public function addCoursepackageRadioSelection($selection)
    {
        $this->coursepackageRadioSelections[] = $selection;
        return $this;
    }

    public function removeCoursepackageRadioSelection($selection)
    {
        $this->coursepackageRadioSelections->removeElement($selection);
        return $this;
    }


    public function getAttendee()
    {
        return $this->attendee;
    }

    public function setAttendee($attendee)
    {
        $this->attendee = $attendee;
        return $this;
    }

    public function getCourses()
    {
        return $this->courses;
    }

    public function setCourses($courses)
    {
        $this->courses = $courses;
        return $this;
    }

    public function addCourse($course)
    {
        $this->courses[] = $course;
        return $this;
    }

    public function removeCourse($course)
    {
        $this->courses->removeElement($course);
        return $this;
    }

    //-------------------------------------------------------
    public function getCoursetypes()
    {
        return $this->coursetypes;
    }

    public function setCoursetypes($coursetypes)
    {
        $this->coursetypes = $coursetypes;
        return $this;
    }

    public function addCoursetype($coursetype)
    {
        $this->coursetypes[] = $coursetype;
        return $this;
    }

    public function removeCoursetype($coursetype)
    {
        $this->coursetypes->removeElement($coursetype);
        return $this;
    }
}
