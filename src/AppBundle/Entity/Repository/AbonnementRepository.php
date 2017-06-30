<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AbonnementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AbonnementRepository extends EntityRepository
{
    public function findAbonnementsForNewEpisode($series)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a, u FROM AppBundle:Abonnement a LEFT JOIN a.user u WHERE a.series = :id AND a.newEpisode = 1')
            ->setParameter('id', $series->getId())
            ->getResult();
    }

    public function findAbonnementsForNewPost($series)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a, u FROM AppBundle:Abonnement a LEFT JOIN a.user u WHERE a.series = :id AND a.newPost = 1')
            ->setParameter('id', $series->getId())
            ->getResult();
    }

    public function findAbonnementsForLivestream($series)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a, u FROM AppBundle:Abonnement a LEFT JOIN a.user u WHERE a.series = :id AND a.livestream = 1')
            ->setParameter('id', $series->getId())
            ->getResult();
    }

    public function findAbonnementsForNewBlogpostComment($series)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a, u FROM AppBundle:Abonnement a LEFT JOIN a.user u WHERE a.series = :id AND a.newCommentOnBlogPost = 1')
            ->setParameter('id', $series->getId())
            ->getResult();
    }

    public function findAbonnementsForNewEpisodeComment($series)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a, u FROM AppBundle:Abonnement a LEFT JOIN a.user u WHERE a.series = :id AND a.newCommentOnEpisode = 1')
            ->setParameter('id', $series->getId())
            ->getResult();
    }

    public function findAbonnementsForFinalizedEpisode($series)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a, u FROM AppBundle:Abonnement a LEFT JOIN a.user u WHERE a.series = :id AND a.encodedEpisode = 1')
            ->setParameter('id', $series->getId())
            ->getResult();
    }

    public function findAbonnementForUserAndSeries($user, $series)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a FROM AppBundle:Abonnement aWHERE a.series = :series_id AND a.user = :user_id')
            ->setParameter('series_id', $series->getId())
            ->setParameter('user_id', $series->getId())
            ->getOneOrNoneResult();
    }
}
