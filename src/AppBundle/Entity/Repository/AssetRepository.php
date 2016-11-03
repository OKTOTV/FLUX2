<?php

namespace AppBundle\Entity\Repository;

use Bprs\AssetBundle\Entity\AssetRepository as BaseRepo;

class AssetRepository extends BaseRepo
{
    public function findAllFiles($number = 5, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a FROM AppBundle:Asset a ORDER BY a.created_at DESC');

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findFilesWithUser($number = 5, $user, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a FROM AppBundle:Asset a WHERE a.owner = :owner_id ORDER BY a.created_at DESC')
            ->setParameter('owner_id', $user->getId());
        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findFilesWithSeries($number = 5, $series = false, $query_only = false)
    {
        $query = null;
        if ($series) {
            $query = $this->getEntityManager()
                ->createQuery('SELECT a FROM AppBundle:Asset a WHERE a.series = :series_id ORDER BY a.created_at DESC')
                ->setParameter('series_id', $series->getId());
        } else {
            $query = $this->getEntityManager()
                ->createQuery('SELECT a FROM AppBundle:Asset a WHERE a.series IS NULL ORDER BY a.created_at DESC');
        }
        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }
}
