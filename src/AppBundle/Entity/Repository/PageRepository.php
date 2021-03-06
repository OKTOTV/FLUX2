<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends EntityRepository
{
    public function findPagesQuery()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Page p');
    }

    public function findActivePages($limit = 0, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Page p WHERE p.isActive = 1');

        if ($query_only) {
            return $query;
        }

        if ($limit) {
            $query->setMaxResults($limit);
        }

        return $query->getResult();
    }
}
