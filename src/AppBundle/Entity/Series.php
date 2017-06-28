<?php

namespace AppBundle\Entity;

use Okto\MediaBundle\Entity\Series as OktoSeries;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\SeriesRepository")
 */
class Series extends OktoSeries
{
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Abonnement", mappedBy="series", cascade={"remove"})
     */
    private $abonnements;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="series", cascade={"remove"})
     */
    protected $posts;

    /**
    * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="channels", fetch="EAGER")
    */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Playlist", mappedBy="series", cascade={"remove"})
     */
    protected $playlists;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Asset", mappedBy="series", cascade={"remove"})
     */
    protected $files;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="managed_series", cascade={"remove"})
     */
    protected $channelmanager;

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

    public function setUsers($users)
    {
        $this->users = $users;
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
     * Add files
     *
     * @param \AppBundle\Entity\Asset $files
     * @return Series
     */
    public function addFile(\AppBundle\Entity\Asset $files)
    {
        $this->files[] = $files;
        $files->setSeries($this);
        return $this;
    }

    /**
     * Remove files
     *
     * @param \AppBundle\Entity\Asset $files
     */
    public function removeFile(\AppBundle\Entity\Asset $files)
    {
        $files->setSeries(Null);
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
}
