<?php
namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Episode;
use Okto\MediaBundle\Entity\Repository\EpisodeRepository as BaseEpisodeRepository;

class EpisodeRepository extends BaseEpisodeRepository
{
    public function findNewerEpisodes(Episode $episode, $numberEpisodes = 3)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM AppBundle:Episode e
                WHERE e.series = :series_id
                AND e.onlineStart > :episode_date
                AND e.isActive = 1
                ORDER BY e.onlineStart ASC'
            )
            ->setParameter('series_id', $episode->getSeries()->getId())
            ->setParameter('episode_date', $episode->getOnlineStart())
            ->setMaxResults($numberEpisodes)
            ->getResult();
    }

    public function findNewestEpisodes($numberEpisodes = 8, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT e, p FROM AppBundle:Episode e
                LEFT JOIN e.posterframe p
                LEFT JOIN e.series s
                WHERE e.isActive = 1
                AND s.isActive = 1
                AND e.onlineStart < :now
                ORDER BY e.onlineStart DESC'
            )
            ->setParameter('now', new \DateTime());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($numberEpisodes)->getResult();
    }

    public function findBestEpisodes($numberEpisodes= 8, $query_only = false)
    {
        $query= $this->createQueryBuilder('e')
            ->addSelect('COUNT(u) AS HIDDEN personCount')
            ->leftJoin('e.users', 'u')
            ->leftJoin('e.posterframe', 'p')
            ->leftJoin('e.series', 's')
            ->groupBy('e')
            ->orderBy('personCount', 'DESC')
            ->where('e.isActive = 1')
            ->andWhere('s.isActive = 1')
            ->andWhere('e.onlineStart < :now')
            ->setParameter('now', new \DateTime())
            ->getQuery();

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($numberEpisodes)->getResult();
    }

    /**
     * returns episodes with most clicks in the last x days days.
     */
    public function findTrendingEpisodes($numberEpisodes = 8, $query_only = false)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT e FROM AppBundle:Episode e
            JOIN e.series s
            WHERE e.isActive = 1
            AND s.isActive = 1
            AND e.onlineStart < :now
            ORDER BY e.trending_score DESC"
            )->setParameter('now', new \DateTime());

        if ($query_only) {
            $query->setMaxResults(60);
            return $query;
        }

        return $query->setMaxResults($numberEpisodes)->getResult();
    }

    public function findEpisodesForSeries($series, $query_only = false)
    {
        $query = $this->getEntityManager()->createQuery(
                    "SELECT e, p FROM AppBundle:Episode e
                    LEFT JOIN e.posterframe p
                    WHERE e.series = :series
                    ORDER BY e.firstranAt DESC"
                )
                ->setParameter('series', $series->getId());

        if ($query_only) {
            return $query;
        }
        return $query->getResult();
    }

    public function findNextEpisode($episode, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM AppBundle:Episode e
                WHERE e.firstranAt > :episode_ran
                AND e.series = :series
                AND e.isActive = true'
            )
            ->setParameter('episode_ran', $episode->getFirstranAt())
            ->setParameter('series', $episode->getSeries())
            ->setMaxResults(1);

        if ($query_only) {
            return $query;
        }
        return $query->getOneOrNullResult();
    }

    public function findPreviousEpisode($episode, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM AppBundle:Episode e
                WHERE e.firstranAt < :episode_ran
                AND e.series = :series
                AND e.isActive = true'
            )
            ->setParameter('episode_ran', $episode->getFirstranAt())
            ->setParameter('series', $episode->getSeries())
            ->setMaxResults(1);

        if ($query_only) {
            return $query;
        }
        return $query->getOneOrNullResult();
    }

    public function findEpisodesByFirstRanAtYear($year = "2005", $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM AppBundle:Episode e
                WHERE e.firstranAt >= :start
                AND e.firstranAt <= :end'
            )
            ->setParameter('start', new \DateTime('01.01.'.$year))
            ->setParameter('end', new \DateTime('31.12.'.$year));

        if ($query_only) {
            return $query;
        }
        return $query->getResult();
    }
}
?>
