<?php

namespace AppBundle\Entity\Repository;

use Bprs\UserBundle\Entity\UserRepository as BaseRepository;
use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends BaseRepository
{
    public function findFavorites($user)
    {
        return $this->findFavoritesQuery($user)->getResult();
    }

    public function findFavoritesQuery($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f FROM AppBundle:Episode f JOIN f.users u JOIN f.series s WHERE u.id = :user_id')
            ->setParameter('user_id', $user->getId());
    }

    public function findPlaylists($user)
    {
        return $this->findPlaylistsQuery($user);
    }

    public function findPlaylistsQuery($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Playlist p JOIN p.user u WHERE u.id = :user_id')
            ->setParameter('user_id', $user->getId());
    }

    public function findChannels($user)
    {
        return $this->findChannelsQuery($user)->getResult();
    }

    public function findChannelsQuery($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM AppBundle:Series s JOIN s.users u WHERE u.id = :user_id')
            ->setParameter('user_id', $user->getId());
    }
}