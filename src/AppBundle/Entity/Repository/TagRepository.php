<?php
namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Tag;

class TagRepository extends EntityRepository
{
    public function findEpisodesWithTag(Tag $tag, $number = 5) {
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM MediaBundle:Episode e JOIN e.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId())
            ->setMaxResults($number)
            ->getResult();
    }

    public function findSeriesWithTag(Tag $tag, $number = 5) {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM MediaBundle:Series s JOIN s.episodes e JOIN e.tags t WHERE t.id = :tag_id GROUP BY t.id')
            ->setParameter('tag_id', $tag->getId())
            ->setMaxResults($number)
            ->getResult();
    }

    public function findPostsWithTag(Tag $tag, $number = 5) {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Post p JOIN p.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId())
            ->setMaxResults($number)
            ->getResult();
    }

    public function findPagesWithTag(Tag $tag, $number = 5) {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Page p JOIN p.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId())
            ->setMaxResults($number)
            ->getResult();
    }

    public function findPlaylistWithTag(Tag $tag, $number = 5) {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM MediaBundle:Playlist p JOIN p.items i JOIN i.episode e JOIN e.tags t WHERE t.id = :tag_id GROUP BY t.id')
            ->setParameter('tag_id', $tag->getId())
            ->setMaxResults($number)
            ->getResult();
    }

    public function findEpisodesWithTagQuery(Tag $tag)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM MediaBundle:Episode e JOIN e.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId());
    }

    public function findSeriesWithTagQuery(Tag $tag)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM MediaBundle:Series s JOIN s.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId());
    }

    public function findPostsWithTagQuery(Tag $tag)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Post p JOIN p.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId());
    }

    public function findPagesWithTagQuery(Tag $tag)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Page p JOIN p.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId());
    }

    public function findPlaylistsWithTagQuery(Tag $tag)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM MediaBundle:Playlist p JOIN p.items i JOIN i.episode e JOIN e.tags t WHERE t.id = :tag_id GROUP BY t.id')
            ->setParameter('tag_id', $tag->getId());
    }

    public function findPopularTags($number = 10)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT t FROM AppBundle:Tag t ORDER BY t.rank DESC')
            ->setMaxResults($number)
            ->getResult();
    }
}
