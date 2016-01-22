<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oktolab\MediaBundle\Entity\Series as BaseSeries;
use AppBundle\Entity\EpisodePin;
/**
 * Series
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Series extends BaseSeries
{

    /**
    *
    * @ORM\OneToMany(targetEntity="Episode", mappedBy="series")
    * @ORM\OrderBy({"onlineStart" = "DESC"})
    */
    private $episodes;

    public function __construct() {
        parent::__construct();
        $this->episodes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\ManyToMany(targetEntity="Post")
     * @ORM\JoinTable(name="series_posts",
     *                  joinColumns={@ORM\JoinColumn(name="series_id", referencedColumnName="id")},
     *                  inverseJoinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id", unique=true)})
     */
    private $posts;

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
}
