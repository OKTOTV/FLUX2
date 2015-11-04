<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SlideRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SlideRepository extends EntityRepository
{
    public function findNewestSlides($numberSlides)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT s FROM AppBundle:Slide s WHERE s.onlineAt >= :startdate ORDER BY e.onlineAt ASC'
            )
            ->setParameter('startdate', new \DateTime())
            ->setMaxResults($numberSlides)
            ->getResult();
    }
}
