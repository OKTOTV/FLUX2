<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Pin as BasePin;
use AppBundle\Entity\Episode;


/**
 * EpisodePin
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class EpisodePin extends BasePin
{
    /**
    * @ORM\ManyToOne(targetEntity="Episode", inversedBy="pins")
    * @ORM\JoinColumn(name="episode_id", referencedColumnName="id")
    */
    private $episode;


    /**
     * Set episode
     *
     * @param \AppBundle\Entity\Episode $episode
     * @return EpisodePin
     */
    public function setEpisode(Episode $episode = null)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return \AppBundle\Entity\Episode
     */
    public function getEpisode()
    {
        return $this->episode;
    }
}
