<?php
namespace MediaBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use MediaBundle\Entity\Episode;

class EpisodeRepository extends EntityRepository
{
    public function findNewerEpisodes(Episode $episode, $numberEpisodes)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM MediaBundle:Episode e WHERE e.series = :series_id AND e.onlineStart > :episode_date AND e.isActive = 1 ORDER BY e.onlineStart ASC'
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
                'SELECT e FROM MediaBundle:Episode e WHERE e.isActive = 1 ORDER BY e.onlineStart DESC'
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

        // return $this->getEntityManager()
        //     ->createQuery(
        //         'SELECT e, COUNT(u) AS HIDDEN favoriteCount FROM MediaBundle:Episode LEFT JOIN e.users u WHERE e.isActive = 1 GROUP BY e ORDER BY favoriteCount DESC'
        //     )
        //     ->setMaxResults($numberEpisodes)
        //     ->getResult();
    }
}
?>
