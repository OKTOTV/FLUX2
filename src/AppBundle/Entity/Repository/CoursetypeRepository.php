<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CoursetypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CoursetypeRepository extends EntityRepository
{
    public function findActiveCoursetypes($query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT c,i FROM AppBundle:Course\Coursetype c
                LEFT JOIN c.image i
                WHERE c.is_active = true
                AND c.highlight = false
                AND SIZE(c.courses) != 0
            ');

        if ($query_only) {
            return $query;
        }
        return $query->getResult();
    }

    public function findHighlightedCoursetypes($query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT c,i FROM AppBundle:Course\Coursetype c
                LEFT JOIN c.image i
                WHERE c.is_active = true
                AND c.highlight = true
                AND SIZE(c.courses) != 0'
            );

        if ($query_only) {
            return $query;
        }

        return $query->getResult();
    }
}
