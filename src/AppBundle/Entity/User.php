<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bprs\UserBundle\Entity\User as BaseUser;
/**
 * IntakeUser
 * @ORM\Table()
 * ORM\Entity(repositoryClass="Bprs\UserBundle\Entity\UserRepository")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UserRepository")
 */
class User extends BaseUser
{
    const ROLE_USER = "ROLE_OKTOLAB_USER";
    const ROLE_PRODUCER = "ROLE_OKTOLAB_PRODUCER";
    const ROLE_BACKEND = "ROLE_OKTOLAB_BACKEND";
    const ROLE_ADMIN = "ROLE_OKTOLAB_ADMIN";

    /**
    * @ORM\OneToMany(targetEntity="Playlist", mappedBy="user")
    */
    private $playlists;

    /**
     * @ORM\ManyToMany(targetEntity="Episode", inversedBy="users")
     * @ORM\JoinTable(name="users_favorites")
     */
    private $favorites;

    /**
     * @ORM\ManyToMany(targetEntity="Series", inversedBy="users")
     * @ORM\JoinTable(name="users_channels")
     */
    private $channels;

    /**
    * @ORM\Column(name="uniqID", type="string", length=13)
    */
    private $uniqID;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    private $comments;

    public function __construct() {
        parent::__construct();
        $this->channels = new \Doctrine\Common\Collections\ArrayCollection();
        $this->favorites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->playlists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uniqID = uniqID();
    }

    /**
     * Add playlists
     *
     * @param \AppBundle\Entity\Playlist $playlists
     * @return User
     */
    public function addPlaylist(\AppBundle\Entity\Playlist $playlists)
    {
        $this->playlists[] = $playlists;

        return $this;
    }

    /**
     * Remove playlists
     *
     * @param \AppBundle\Entity\Playlist $playlists
     */
    public function removePlaylist(\AppBundle\Entity\Playlist $playlists)
    {
        $this->playlists->removeElement($playlists);
    }

    /**
     * Get playlists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlaylists()
    {
        return $this->playlists;
    }

    /**
     * Set uniqID
     *
     * @param string $uniqID
     * @return User
     */
    public function setUniqID($uniqID)
    {
        $this->uniqID = $uniqID;

        return $this;
    }

    /**
     * Get uniqID
     *
     * @return string
     */
    public function getUniqID()
    {
        return $this->uniqID;
    }

    /**
     * Add favorites
     *
     * @param \AppBundle\Entity\Episode $favorites
     * @return User
     */
    public function addFavorite(\AppBundle\Entity\Episode $favorites)
    {
        $this->favorites[] = $favorites;

        return $this;
    }

    /**
     * Remove favorites
     *
     * @param \AppBundle\Entity\Episode $favorites
     */
    public function removeFavorite(\AppBundle\Entity\Episode $favorites)
    {
        $this->favorites->removeElement($favorites);
    }

    /**
     * Get favorites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

    /**
     * Add comments
     *
     * @param \AppBundle\Entity\Comment $comments
     * @return User
     */
    public function addComment(\AppBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \AppBundle\Entity\Comment $comments
     */
    public function removeComment(\AppBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add channels
     *
     * @param \AppBundle\Entity\Series $channels
     * @return User
     */
    public function addChannel(\AppBundle\Entity\Series $channels)
    {
        $this->channels[] = $channels;
        $channels->addUser($this);
        return $this;
    }

    /**
     * Remove channels
     *
     * @param \AppBundle\Entity\Series $channels
     */
    public function removeChannel(\AppBundle\Entity\Series $channels)
    {
        $this->channels->removeElement($channels);
    }

    /**
     * Get channels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChannels()
    {
        return $this->channels;
    }
}
