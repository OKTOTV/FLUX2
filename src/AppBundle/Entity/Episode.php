<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oktolab\MediaBundle\Entity\Episode as BaseEpisode;
use AppBundle\Entity\EpisodePin;
/**
 * Episode
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Episode extends BaseEpisode
{
    /**
    * @ORM\OneToMany(targetEntity="EpisodePin", mappedBy="episode", cascade={"remove"})
    */
    private $pins;

    /**
    * @ORM\ManyToOne(targetEntity="Series", inversedBy="episodes", cascade={"persist"})
    */
    private $series;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->pins = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pins
     *
     * @param \AppBundle\Entity\EpisodePin $pins
     * @return Episode
     */
    public function addPin(EpisodePin $pins)
    {
        $this->pins[] = $pins;

        return $this;
    }

    /**
     * Remove pins
     *
     * @param \AppBundle\Entity\EpisodePin $pins
     */
    public function removePin(EpisodePin $pins)
    {
        $this->pins->removeElement($pins);
    }

    /**
     * Get pins
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPins()
    {
        return $this->pins;
    }

    /**
     * Set series
     *
     * @param \Oktolab\MediaBundle\Entity\Series $series
     * @return Episode
     */
    public function setSeries($series = null)
    {
        $this->series = $series;
        return $this;
    }

    /**
     * Get series
     *
     * @return \Oktolab\MediaBundle\Entity\Series
     */
    public function getSeries()
    {
        return $this->series;
    }
}
