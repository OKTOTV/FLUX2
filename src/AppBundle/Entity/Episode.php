<?php

namespace AppBundle\Entity;

use Okto\MediaBundle\Entity\Episode as OktoEpisode;
use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\EpisodeRepository")
 */
class Episode extends OktoEpisode {
    /**
    * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="favorites")
    */
    private $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users
     *
     * @param \AppBundle\Entity\User $users
     * @return Episode
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
