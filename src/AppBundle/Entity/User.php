<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bprs\UserBundle\Entity\User as BaseUser;
/**
 * IntakeUser
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bprs\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{

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
    * @ORM\Column(name="uniqID", type="string", length=13)
    */
    private $uniqID;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    private $comments;

    public function __construct() {
        parent::__construct();
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
}
