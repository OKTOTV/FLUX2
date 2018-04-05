<?php

namespace AppBundle\Entity;

use Okto\MediaBundle\Entity\Episode as OktoEpisode;
use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\EpisodeRepository")
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 */
class Episode extends OktoEpisode {
    /**
    * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="favorites")
    */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Oktolab\MediaBundle\Entity\Playlistitem", mappedBy="episode", cascade={"remove"})
     */
    private $playlistitems;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EpisodeComment", mappedBy="episode")
     */
    private $comments;

    /**
     * @ORM\Column(name="views", type="integer", options={"defaults" = 0}, nullable=true)
     */
    private $views;

    /**
     * @ORM\Column(name="trending_score", type="integer", options={"defaults" = 0}, nullable=true)
     * used to offload the query to set score. a cronjob should update this score every few hours.
     */
    private $trending_score;

    public function __construct()
    {
        parent::__construct();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->views = 0;
        $this->trending_score = 0;
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

    /**
     * @JMS\Type("string")
     * @JMS\Groups({"search"})
     * @JMS\VirtualProperty
     * @JMS\SerializedName("series_search")
     */
    public function getSeriesSearch()
    {
        if ($this->getSeries()) {
            return $this->getSeries()->getName();
        }
        return "";
    }

    /**
     * @JMS\Type("string")
     * @JMS\Groups({"search"})
     * @JMS\VirtualProperty
     * @JMS\SerializedName("episode_tags")
     */
    public function getEpisodeTagSearch()
    {
        if (count($this->getTags())) {
            return implode(", ", $this->getTags()->toArray());
        }
        return "";
    }

    public function canBeOnline()
    {
        $now = new \DateTime();
        if (
            $this->getIsActive() &&
            $this->getSeries()->getIsActive() &&
            $now >= $this->getOnlineStart() &&
            ($now <= $this->getOnlineEnd() || $this->getOnlineEnd() == null)
        ) {
            return true;
        }
        return false;
    }

    /**
     * Add playlistitems
     *
     * @param \Oktolab\MediaBundle\Entity\Playlistitem $playlistitems
     * @return Episode
     */
    public function addPlaylistitem(\Oktolab\MediaBundle\Entity\Playlistitem $playlistitems)
    {
        $this->playlistitems[] = $playlistitems;

        return $this;
    }

    /**
     * Remove playlistitems
     *
     * @param \Oktolab\MediaBundle\Entity\Playlistitem $playlistitems
     */
    public function removePlaylistitem(\Oktolab\MediaBundle\Entity\Playlistitem $playlistitems)
    {
        $this->playlistitems->removeElement($playlistitems);
    }

    /**
     * Get playlistitems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlaylistitems()
    {
        return $this->playlistitems;
    }

    /**
     * Add comments
     *
     * @param \AppBundle\Entity\EpisodeComment $comment
     * @return Episode
     */
    public function addComment(\AppBundle\Entity\EpisodeComment $comment)
    {
        $this->comments[] = $comment;
        $comment->setEpisode($this);
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \AppBundle\Entity\EpisodeComment $comment
     */
    public function removeComment(\AppBundle\Entity\EpisodeComment $comment)
    {
        $this->comments->removeElement($comment);
        $comment->setEpisode(null);
        return $this;
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

    public function getViews()
    {
        return $this->views;
    }

    public function setViews($views)
    {
        $this->views = $views;
        return $this;
    }

    public function getTrendingScore()
    {
        return $this->trending_score;
    }

    public function setTrendingScore($score)
    {
        $this->trending_score = $score;
    }

    public function isNew($relative = "-3 days")
    {
        return $this->getFirstranAt() > new \DateTime($relative);
    }
}
