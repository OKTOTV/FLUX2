<?php

namespace AppBundle\Twig;

class AcademyExtension extends \Twig_Extension
{
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('hours', array($this, 'hoursFilter'))
        );
    }

    public function hoursFilter($coursedates)
    {
        $time = null;
        foreach ($coursedates as $coursedate) {
            $time = $time + ($coursedate->getCourseEnd()->format('U') - $coursedate->getCourseStart()->format('U'));
        }

        return ceil($time / 3600);
    }

    public function getName() {
        return 'oktothek_extension';
    }
}
