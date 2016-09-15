<?php
namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Okto\MediaBundle\Entity\Tag;

class TagRepository extends EntityRepository
{
    public function findPostsWithTag(Tag $tag, $number = 5, $query_only = false) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Post p JOIN p.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findPagesWithTag(Tag $tag, $number = 5, $query_only = false) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Page p JOIN p.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findHighlightedTags($number = 6, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT t FROM OktoMediaBundle:Tag t WHERE t.highlight = 1 ORDER BY t.rank ASC');

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }
}
