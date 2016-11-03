<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bprs\AssetBundle\Entity\Asset as BaseAsset;

/**
 * Asset
 *
 * @ORM\Table()
* @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AssetRepository")
 */
class Asset extends BaseAsset
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="files")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Series", inversedBy="files")
     * @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     */
    protected $series;

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($user)
    {
        $this->owner = $user;
        return $this;
    }

    public function getSeries()
    {
        return $this->series;
    }

    public function setSeries($series)
    {
        $this->series = $series;
        return $this;
    }
}
