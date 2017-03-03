<?php

namespace AppBundle\Entity\Repository;

use Bprs\AssetBundle\Entity\AssetRepository as BaseRepo;

class AttendeeRepository extends BaseRepo
{
    public function findAllAttendees($number = 5, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a FROM AppBundle:Course\Attendee a');

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }
}
