<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
    public function findPinnedPosts($numberOfPosts = 4)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Post p WHERE p.isActive = :active AND p.onlineAt < :now AND p.pinned = :pinned')
            ->setParameter('active', true)
            ->setParameter('pinned', true)
            ->setParameter('now', new \DateTime())
            ->setMaxResults($numberOfPosts)
            ->getResult();
    }

    public function findActivePostQuery()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Post p WHERE p.isActive = :active AND p.onlineAt < :now')
            ->setParameter('active', true)
            ->setParameter('now', new \DateTime());
    }

    public function findAllPostQuery()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Post p WHERE p.series IS NULL');
    }

    public function findAllBlogPost($number, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM AppBundle:Post p
                WHERE p.series IS NOT NULL
                ORDER BY p.createdAt DESC'
            );

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findPostsForSeries($series)
    {
        return $this->findPostsForSeriesQuery($series)->getResult();
    }

    public function findPostsForSeriesQuery($series)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Post p WHERE p.series = :id')
            ->setParameter('id', $series->getId());
    }

    public function findNewestPosts($number = 5, $series = false, $query_only = false )
    {
        $query = null;
        if ($series) {
            $query = $this->getEntityManager()
                ->createQuery('SELECT p FROM AppBundle:Post p WHERE p.series = :id ORDER BY p.createdAt DESC')
                ->setParameter('id', $series->getId());
        } else {
            $query = $this->getEntityManager()
                ->createQuery('SELECT p FROM AppBundle:Post p WHERE p.series IS NULL ORDER BY p.createdAt DESC');
        }

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }
}
