<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oktolab\MediaBundle\Entity\Episode as BaseEpisode;
use AppBundle\Entity\EpisodePin;
/**
 * Episode
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EpisodeRepository")
 */
class Episode extends BaseEpisode
{
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
