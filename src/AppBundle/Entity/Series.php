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
    }

    /**
     * Add episodes
     *
     * @param \Oktolab\MediaBundle\Entity\Episode $episodes
     * @return Series
     */
    public function addEpisode(\Oktolab\MediaBundle\Entity\Episode $episodes)
    {
        $this->episodes[] = $episodes;
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
}
