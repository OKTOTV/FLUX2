<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CourseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CourseRepository extends EntityRepository
{
    public function findCoursesForCoursetypeQuery($coursetype)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM AppBundle:Course\Course c WHERE c.coursetype = :coursetype')
            ->setParameter('coursetype', $coursetype->getId());
    }

    public function findFutureCourses($query_only = false)
    {
        $date = new \DateTime();
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT c,d FROM AppBundle:Course\Course c
                LEFT JOIN c.dates d
                WHERE c.is_active = true
                AND c.deadline > :now
                AND c.max_attendees > SIZE(c.attendees)'
            )
            ->setParameter('now', $date->format('Y-m-d'));

        if ($query_only) {
            return $query;
        }

        return $query->getResult();
    }

    public function findFutureCoursesForType($coursetype, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                "SELECT c FROM AppBundle:Course\Course c
                LEFT JOIN c.dates d
                WHERE c.coursetype = :coursetype
                AND c.is_active = true
                AND c.deadline > :now
                AND c.max_attendees > SIZE(c.attendees)"
            )
            ->setParameter('now', new \DateTime())
            ->setParameter('coursetype', $coursetype->getId());

        if ($query_only) {
            return $query;
        }
        return $query->getResult();
    }
}
