<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bprs\AssetBundle\Entity\Asset as BaseAsset;

/**
 * Asset
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Asset extends BaseAsset
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="episode", type="string", length=255)
     */
    private $episode;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set episode
     *
     * @param string $episode
     * @return Asset
     */
    public function setEpisode($episode)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return string
     */
    public function getEpisode()
    {
        return $this->episode;
    }
}
