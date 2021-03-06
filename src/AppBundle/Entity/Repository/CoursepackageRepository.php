<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CoursetypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CoursepackageRepository extends EntityRepository
{
    // TODO: filter courspackages which have coursetypes with courses in the future
    public function findActiveCoursepackages($query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT cp,i,ct,c,cd FROM AppBundle:Course\Coursepackage cp
                LEFT JOIN cp.image i
                LEFT JOIN cp.coursetypes ct
                LEFT JOIN ct.courses c
                LEFT JOIN c.dates cd
                WHERE cp.is_active = true
                AND ct.is_active = true
                AND cd.courseStart >= :now
            ')->setParameter("now", new \DateTime());

        if ($query_only) {
            return $query;
        }
        return $query->getResult();
    }
}
