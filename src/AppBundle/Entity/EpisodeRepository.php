<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EpisodeRepository extends EntityRepository
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
                'SELECT e FROM AppBundle:Episode e WHERE e.isActive = 1 ORDER BY e.onlineStart ASC'
            )
            ->setMaxResults($numberEpisodes)
            ->getResult();
    }
}
?>
