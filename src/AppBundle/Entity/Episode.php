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
    * @ORM\OneToMany(targetEntity="EpisodePin", mappedBy="episode")
    */
    private $pins;

    /**
    * @ORM\OneToOne(targetEntity="Asset")
    * @ORM\JoinColumn(name="posterframe_id", referencedColumnName="id")
    */
    private $posterframe;

    /**
    * @ORM\OneToOne(targetEntity="Asset")
    * @ORM\JoinColumn(name="video_id", referencedColumnName="id")
    */
    private $video;

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
     * Set posterframe
     *
     * @param \AppBundle\Entity\Asset $posterframe
     * @return Episode
     */
    public function setPosterframe(\AppBundle\Entity\Asset $posterframe = null)
    {
        $this->posterframe = $posterframe;

        return $this;
    }

    /**
     * Get posterframe
     *
     * @return \AppBundle\Entity\Asset
     */
    public function getPosterframe()
    {
        return $this->posterframe;
    }

    /**
     * Set video
     *
     * @param \AppBundle\Entity\Asset $video
     * @return Episode
     */
    public function setVideo(\AppBundle\Entity\Asset $video = null)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return \AppBundle\Entity\Asset
     */
    public function getVideo()
    {
        return $this->video;
    }
}
