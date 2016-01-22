<?php
namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Tag;

class TagRepository extends EntityRepository
{
    public function findEpisodesWithTag(Tag $tag) {
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM AppBundle:Episode e JOIN e.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId())
            ->setMaxResults(5)
            ->getResult();
    }

    public function findSeriesWithTag(Tag $tag) {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM AppBundle:Series s JOIN s.episodes e JOIN e.tags t WHERE t.id = :tag_id GROUP BY t.id')
            ->setParameter('tag_id', $tag->getId())
            ->setMaxResults(5)
            ->getResult();
    }

    public function findPostsWithTag(Tag $tag) {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Post p JOIN p.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId())
            ->setMaxResults(5)
            ->getResult();
    }

    public function findNewsWithTag(Tag $tag) {
        return $this->getEntityManager()
            ->createQuery('SELECT n FROM AppBundle:News n JOIN n.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId())
            ->setMaxResults(5)
            ->getResult();
    }

    public function findPagesWithTag(Tag $tag) {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Page p JOIN p.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId())
            ->setMaxResults(5)
            ->getResult();
    }

    public function findPlaylistWithTag(Tag $tag) {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Playlist p JOIN p.items i JOIN i.episode e JOIN e.tags t WHERE t.id = :tag_id GROUP BY t.id')
            ->setParameter('tag_id', $tag->getId())
            ->setMaxResults(5)
            ->getResult();
    }

    public function findPopularTags($number = 10)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT t FROM AppBundle:Tag t ORDER BY t.rank DESC')
            ->setMaxResults($number)
            ->getResult();
    }
}


// public function findNewerEpisodes(Episode $episode, $numberEpisodes)
// {
//     return $this->getEntityManager()
//         ->createQuery(
//             'SELECT e FROM AppBundle:Episode e WHERE e.series = :series_id AND e.onlineStart > :episode_date AND e.isActive = 1 ORDER BY e.onlineStart ASC'
//         )
//         ->setParameter('series_id', $episode->getSeries()->getId())
//         ->setParameter('episode_date', $episode->getOnlineStart())
//         ->setMaxResults($numberEpisodes)
//         ->getResult();
// }
