<?php
namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Tag;
use MediaBundle\Entity\Series;
use Doctrine\ORM\Query\ResultSetMapping;

class SeriesRepository extends EntityRepository
{
    public function findEpisodesWithTag(Series $series, Tag $tag, $pagerable = false) {
        if ($pagerable) {
            return $this->getEntityManager()
                ->createQuery('SELECT e FROM MediaBundle:Episode JOIN AppBundle:Tag t WHERE e.onlineStart > :online_start AND e.is_active = 1 AND e.series = :series_id AND t.id = :tag_id')
                ->setParameter('tag_id', $tag->getId())
                ->setParameter('online_start', new \DateTime())
                ->setParameter('series_id', $series->getId());
        }
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM MediaBundle:Episode JOIN e.tags t WHERE t.id = :tag_id AND e.series = :series_id ORDER BY e.onlineStart DESC')
            ->setParameter('tag_id', $tag->getId())
            ->setParameter('series_id', $series->getId())
            ->setMaxResults(5)
            ->getResult();
    }

    public function getSeriesTags(Series $series)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('AppBundle:Tag', 't');
        $rsm->addFieldResult('t', 'id', 'id');
        $rsm->addFieldResult('t', 'slug', 'slug');
        $rsm->addFieldResult('t', 'text', 'text');
        $rsm->addFieldResult('t', 'rank', 'rank');

        return $this->getEntityManager()
            ->createNativeQuery('SELECT t.id, t.slug, t.text ,t.rank FROM tag t JOIN episode_tags et ON t.id = et.tag_id JOIN episode e ON et.episode_id = e.id WHERE e.series_id = 1  AND e.is_active = ? AND (e.online_start > ? OR e.online_start IS NULL) GROUP BY t.id ORDER BY t.rank DESC', $rsm)
            ->setParameter(1, $series->getId())
            ->setParameter(2, new \DateTime())
            ->getResult();
    }

    public function findNewestEpisodesForSeries(Series $series, $numberEpisodes = 5)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM MediaBundle:Episode e WHERE e.series = :series_id  AND e.isActive = 1 AND (e.onlineStart > :episode_date OR e.onlineStart IS NULL) ORDER BY e.onlineStart DESC'
            )
            ->setParameter('series_id', $series->getId())
            ->setParameter('episode_date', new \DateTime())
            ->setMaxResults($numberEpisodes)
            ->getResult();
    }
}
