<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends EntityRepository
{
    public function findComments($results = 10, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT c FROM AppBundle:Comment c ORDER BY c.createdAt DESC');

        if ($query_only) {
            return $query;
        }

        return $query->setMaxResults($results)->getResult();
    }
}
