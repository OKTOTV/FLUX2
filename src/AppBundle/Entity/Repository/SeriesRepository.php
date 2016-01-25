<?php
namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Series;

class SeriesRepository extends EntityRepository
{
    public function findEpisodesWithTag(Series $series, Tag $tag, $pagerable = false) {
        if ($pagerable) {
            return $this->getEntityManager()
                ->createQuery('SELECT e FROM AppBundle:Episode e JOIN AppBundle:Tag t WHERE e.onlineStart > :online_start AND e.is_active = 1 AND e.series = :series_id AND t.id = :tag_id')
                ->setParameter('tag_id', $tag->getId())
                ->setParameter('online_start', new \DateTime())
                ->setParameter('series_id', $series->getId());
        }
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM AppBundle:Episode e JOIN e.tags t WHERE t.id = :tag_id AND e.series = :series_id ORDER BY e.onlineStart DESC')
            ->setParameter('tag_id', $tag->getId())
            ->setParameter('series_id', $series->getId())
            ->setMaxResults(5)
            ->getResult();
    }

    public function getSeriesTags(Series $series)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT t FROM AppBundle:Tag t JOIN AppBundle:Episode e WHERE e.series = :series_id GROUP BY t.id')
            ->setParameter('series_id', $series->getId())
            ->getResult();
    }

    public function findNewestEpisodesForSeries(Series $series, $numberEpisodes = 5)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM AppBundle:Episode e WHERE e.series = :series_id  AND e.isActive = 1 AND (e.onlineStart > :episode_date OR e.onlineStart IS NULL) ORDER BY e.onlineStart DESC'
            )
            ->setParameter('series_id', $series->getId())
            ->setParameter('episode_date', new \DateTime())
            ->setMaxResults($numberEpisodes)
            ->getResult();
    }
}
