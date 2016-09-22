<?php
namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Episode;
use Oktolab\MediaBundle\Entity\Repository\BaseEpisodeRepository;

class EpisodeRepository extends BaseEpisodeRepository
{
    public function findNewerEpisodes(Episode $episode, $numberEpisodes)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM AppBundle:Episode e WHERE e.series = :series_id AND e.onlineStart > :episode_date AND e.isActive = 1 ORDER BY e.onlineStart ASC'
            )
            ->setParameter('series_id', $episode->getSeries()->getId())
            ->setParameter('episode_date', $episode->getOnlineStart())
            ->setMaxResults($numberEpisodes)
            ->getResult();
    }

    public function findNewestEpisodes($numberEpisodes)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM AppBundle:Episode e WHERE e.isActive = 1 ORDER BY e.onlineStart DESC'
            )
            ->setMaxResults($numberEpisodes)
            ->getResult();
    }

    public function findTopEpisodes($numberEpisodes)
    {
        return $this->createQueryBuilder('e')
            ->addSelect('COUNT(u) AS HIDDEN personCount')
            ->leftJoin('e.users', 'u')
            ->groupBy('e')
            ->orderBy('personCount', 'DESC')
            ->where('e.isActive = 1')
            ->getQuery()
            ->setMaxResults($numberEpisodes)
            ->getResult();
    }

    public function findEpisodesForSeries($series)
    {
        return $this->findEpisodesForSeriesQuery($series)->getResult();
    }

    public function findEpisodesForSeriesQuery($series)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM AppBundle:Episode e WHERE e.series = :series'
            )
            ->setParameter('series', $series->getId());
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
}
?>
