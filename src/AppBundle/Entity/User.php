<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bprs\UserBundle\Entity\User as BaseUser;
use AppBundle\Entity\Series;
use AppBundle\Entity\Playlist;
use AppBundle\Entity\Episode;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Abonnement;
use AppBundle\Entity\Notification;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UserRepository")
 */
class User extends BaseUser
{
    const ROLE_USER = "ROLE_OKTOLAB_USER";
    const ROLE_PRODUCER = "ROLE_OKTOLAB_PRODUCER";
    const ROLE_BACKEND = "ROLE_OKTOLAB_BACKEND";
    const ROLE_ADMIN = "ROLE_OKTOLAB_ADMIN";

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Playlist", mappedBy="user")
    */
    private $playlists;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Episode", inversedBy="users")
     * @ORM\JoinTable(name="users_favorites")
     */
    private $favorites;

    /**
     * @ORM\OneToMany(targetEntity="Abonnement", mappedBy="user")
     */
    private $abonnements;

    /**
    * @ORM\Column(name="uniqID", type="string", length=13)
    */
    private $uniqID;

    /**
     * series producers own
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Series", inversedBy="users")
     * @ORM\JoinTable(name="users_series")
     */
    private $channels;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Asset", mappedBy="owner")
     */
    private $files;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EpisodeComment", mappedBy="user")
     */
    private $episode_comments;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PostComment", mappedBy="user")
     */
    private $post_comments;

    /**
     * @ORM\Column(name="greeted", type="boolean", options={"default" : false}, nullable=true)
     */
    private $greeted;

    public function __construct() {
        parent::__construct();
        $this->abonnements = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->episode_comments = new ArrayCollection();
        $this->$post_comments = new ArrayCollection();
        $this->uniqID = uniqID();
    }

    public function __toString()
    {
        return $this->getUsername();
    }

     public function getEpisodeComments()
     {
         return $this->episode_comments;
     }

     public function addEpisodeComment($comment)
     {
         $this->episode_comments[] = $comment;
         return $this;
     }

     public function removeEpisodeComment($comment)
     {
         $this->episode_comments->removeElement($comment);
         return $this;
     }

     public function getPostComments()
     {
         return $this->post_comments;
     }

     public function addPostComment($comment)
     {
         $this->post_comments[] = $comment;
         return $this;
     }

     public function removePostComment($comment)
     {
         $this->post_comments->removeElement($comment);
         return $this;
     }

    /**
     * Add playlists
     *
     * @param \AppBundle\Entity\Playlist $playlists
     * @return User
     */
    public function addPlaylist(Playlist $playlists)
    {
        $this->playlists[] = $playlists;

        return $this;
    }

    /**
     * Remove playlists
     *
     * @param \AppBundle\Entity\Playlist $playlists
     */
    public function removePlaylist(Playlist $playlists)
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
     * @param \MediaBundle\Entity\Episode $favorites
     * @return User
     */
    public function addFavorite(Episode $favorites)
    {
        $this->favorites[] = $favorites;

        return $this;
    }

    /**
     * Remove favorites
     *
     * @param \MediaBundle\Entity\Episode $favorites
     */
    public function removeFavorite(Episode $favorites)
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
     * Add abonnements
     *
     * @param \AppBundle\Entity\Abonnements $abonnements
     * @return User
     */
    public function addAbonnement(Abonnement $abonnements)
    {
        $this->abonnements[] = $abonnements;

        return $this;
    }

    /**
     * Remove abonnements
     *
     * @param \AppBundle\Entity\Abonnements $abonnements
     */
    public function removeAbonnement(Abonnement $abonnements)
    {
        $this->abonnements->removeElement($abonnements);
    }

    /**
     * Get abonnements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAbonnements()
    {
        return $this->abonnements;
    }

    /**
     * Add channels
     *
     * @param \MediaBundle\Entity\Series $channels
     * @return User
     */
    public function addChannel(Series $channels)
    {
        $this->channels[] = $channels;

        return $this;
    }

    /**
     * Remove channels
     *
     * @param \MediaBundle\Entity\Series $channels
     */
    public function removeChannel(Series $channels)
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

    /**
     * Add files
     *
     * @param \AppBundle\Entity\Asset $files
     * @return User
     */
    public function addFile(\AppBundle\Entity\Asset $files)
    {
        $this->files[] = $files;
        $files->setOwner($this);
        return $this;
    }

    /**
     * Remove files
     *
     * @param \AppBundle\Entity\Asset $files
     */
    public function removeFile(\AppBundle\Entity\Asset $files)
    {
        $files->setOwner(Null);
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    public function getGreeted()
    {
        return $this->greeted;
    }

    public function setGreeted($greeted)
    {
        return $this->greeted = $greeted;
    }
}
