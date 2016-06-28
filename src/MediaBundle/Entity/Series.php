<?php

namespace MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oktolab\MediaBundle\Entity\Series as BaseSeries;
use Doctrine\Common\Collections\ArrayCollection;
use MediaBundle\Entity\Reachme;

/**
 * Series
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MediaBundle\Entity\Repository\SeriesRepository")
 */
class Series extends BaseSeries
{
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Abonnement", mappedBy="series", cascade="remove")
     */
    private $abonnements;

    /**
    *
    * @ORM\OneToMany(targetEntity="Episode", mappedBy="series", cascade="remove")
    * @ORM\OrderBy({"onlineStart" = "DESC"})
    */
    private $episodes;

    public function __construct() {
        parent::__construct();
        $this->abonnements = new ArrayCollection();
        $this->episodes = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->reachmes = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="series")
     */
    private $posts;

    /**
    * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="channels", fetch="EAGER")
    */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Playlist", mappedBy="series")
     */
    private $playlists;

    /**
     * @ORM\OneToMany(targetEntity="MediaBundle\Entity\Reachme", mappedBy="series")
     */
    private $reachmes;

    /**
     * Add episodes
     *
     * @param \Oktolab\MediaBundle\Entity\Episode $episodes
     * @return Series
     */
    public function addEpisode(\Oktolab\MediaBundle\Entity\Episode $episodes)
    {
        $this->episodes[] = $episodes;
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        return $this;
    }

    /**
     * Remove episodes
     *
     * @param \Oktolab\MediaBundle\Entity\Episode $episodes
     */
    public function removeEpisode(\Oktolab\MediaBundle\Entity\Episode $episodes)
    {
        $this->episodes->removeElement($episodes);
    }

    /**
     * Get episodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpisodes()
    {
        return $this->episodes;
    }

    public function setEpisodes($episodes)
    {
        $this->episodes = $episodes;
        return $this;
    }

    /**
     * Add posts
     *
     * @param \AppBundle\Entity\Post $posts
     * @return Series
     */
    public function addPost(\AppBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
        $posts->setSeries($this);
        return $this;
    }

    /**
     * Remove posts
     *
     * @param \AppBundle\Entity\Post $posts
     */
    public function removePost(\AppBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * Add users
     *
     * @param \AppBundle\Entity\User $users
     * @return Series
     */
    public function addUser(\AppBundle\Entity\User $users)
    {
        $this->users[] = $users;
        $users->addChannel($this);
        return $this;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\User $users
     */
    public function removeUser(\AppBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
        $users->removeChannel($this);
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

    /**
     * Add abonnements
     *
     * @param \AppBundle\Entity\Abonnements $abonnements
     * @return Series
     */
    public function addAbonnement(\AppBundle\Entity\Abonnement $abonnements)
    {
        $this->abonnements[] = $abonnements;

        return $this;
    }

    /**
     * Remove abonnements
     *
     * @param \AppBundle\Entity\Abonnements $abonnements
     */
    public function removeAbonnement(\AppBundle\Entity\Abonnement $abonnements)
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
     * Add playlists
     *
     * @param \AppBundle\Entity\Playlist $playlists
     * @return Series
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
     * Add reachmes
     *
     * @param \AppBundle\Entity\Reachme $reachmes
     * @return Series
     */
    public function addReachme(Reachme $reachmes)
    {
        $this->reachmes[] = $reachmes;

        return $this;
    }

    /**
     * Remove reachmes
     *
     * @param \AppBundle\Entity\Reachme $reachmes
     */
    public function removeReachme(Reachme $reachmes)
    {
        $this->reachmes->removeElement($reachmes);
    }

    /**
     * Get reachmes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReachmes()
    {
        return $this->reachmes;
    }
}
