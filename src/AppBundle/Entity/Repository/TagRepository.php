<?php
namespace AppBundle\Entity\Repository;

use Okto\MediaBundle\Entity\Repository\TagRepository as OktoTagRepository;
use Okto\MediaBundle\Entity\Tag;

class TagRepository extends OktoTagRepository
{
    public function findEpisodesWithTag(Tag $tag, $number = 6, $query_only = false, $episode_class = "AppBundle:Episode") {
        $query = $this->getEntityManager()
            ->createQuery('SELECT e FROM '.$episode_class.' e JOIN e.tags t WHERE t.id = :tag_id ORDER BY e.firstranAt  DESC')
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findSeriesWithTag(Tag $tag, $number = 6, $query_only = false, $series_class = "AppBundle:Series") {
        $query = $this->getEntityManager()
            ->createQuery('SELECT s FROM '.$series_class.' s LEFT JOIN s.episodes e LEFT JOIN e.tags t WHERE t.id = :tag_id ORDER BY s.createdAt DESC')
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findPlaylistsWithTag(Tag $tag, $number = 6, $query_only = false) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Playlist p JOIN p.items i JOIN i.episode e JOIN e.tags t WHERE t.id = :tag_id ORDER BY p.createdAt DESC')
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findPostsWithTag(Tag $tag, $number = 6, $query_only = false) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Post p JOIN p.tags t WHERE t.id = :tag_id ORDER BY p.createdAt DESC')
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findPagesWithTag(Tag $tag, $number = 6, $query_only = false) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Page p JOIN p.tags t WHERE t.id = :tag_id ORDER BY p.updatedAt DESC')
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findHighlightedTags($number = 6, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT t FROM AppBundle:Tag t WHERE t.highlight = 1 ORDER BY t.rank ASC');

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }
}
