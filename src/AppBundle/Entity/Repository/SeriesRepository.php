<?php
namespace AppBundle\Entity\Repository;

use Okto\MediaBundle\Entity\Tag;
use AppBundle\Entity\Series;
use Doctrine\ORM\Query\ResultSetMapping;
use Oktolab\MediaBundle\Entity\Repository\BaseSeriesRepository;

class SeriesRepository extends BaseSeriesRepository
{
    public function findEpisodesWithTag(Series $series, Tag $tag, $pagerable = false, $number = 9) {
        if ($pagerable) {
            return $this->getEntityManager()
                ->createQuery(
                    'SELECT e FROM AppBundle:Episode e
                    JOIN AppBundle:Tag t
                    LEFT JOIN e.series s
                    WHERE (e.onlineStart < :online_start OR e.onlineStart IS NULL)
                    AND e.isActive = 1
                    AND s.isActive = 1
                    AND e.series = :series_id
                    AND t.id = :tag_id'
                )
                ->setParameter('tag_id', $tag->getId())
                ->setParameter('online_start', new \DateTime())
                ->setParameter('series_id', $series->getId());
        }
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM AppBundle:Episode e
                JOIN e.tags t
                LEFT JOIN e.series s
                WHERE t.id = :tag_id
                AND e.series = :series_id
                AND e.isActive = 1
                AND s.isActive = 1
                AND (e.onlineStart < :online_start OR e.onlineStart IS NULL)
                ORDER BY e.firstranAt DESC'
            )
            ->setParameter('tag_id', $tag->getId())
            ->setParameter('online_start', new \DateTime())
            ->setParameter('series_id', $series->getId())
            ->setMaxResults($number)
            ->getResult();
    }

    public function getSeriesTags(Series $series)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT t from AppBundle:Tag t
                JOIN t.episodes e
                JOIN e.series s
                WHERE s.id = :series_id
                AND e.isActive = true
                AND (e.onlineStart < :start OR e.onlineStart IS NULL)'
            )
            ->setParameter('series_id', $series->getId())
            ->setParameter('start', new \DateTime())
            ->getResult();
    }

    public function findNewestEpisodesForSeries(Series $series, $numberEpisodes = 9)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM AppBundle:Episode e
                LEFT JOIN e.series s
                WHERE e.series = :series_id
                AND e.isActive = 1
                AND s.isActive = 1
                AND (e.onlineStart < :now OR e.onlineStart IS NULL)
                ORDER BY e.firstranAt DESC'
            )
            ->setParameter('series_id', $series->getId())
            ->setParameter('now', new \DateTime())
            ->setMaxResults($numberEpisodes)
            ->getResult();
    }

    public function findActiveEpisodes(Series $series, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                "SELECT e FROM AppBundle:Episode e
                WHERE e.series = :series_id
                AND (e.onlineStart < :online_start OR e.onlineStart IS NULL)
                AND e.isActive = 1
                ORDER BY e.firstranAt DESC"
            )->setParameter('series_id', $series->getId())
            ->setParameter('online_start', new \DateTime());

        if ($query_only) {
            return $query;
        }

        return $query->getResult();
    }
}
