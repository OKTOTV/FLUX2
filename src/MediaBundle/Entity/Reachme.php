<?php

namespace MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MediaBundle\Entity\Series;

/**
 * Reachme
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Reachme
{
    const TYPE_MAIL = 0;
    const TYPE_FB = 1;
    const TYPE_TWITTER = 2;
    const TYPE_INST = 3;
    const TYPE_GP   = 4;

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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="uri", type="string", length=255)
     */
    private $uri;

    /**
     * @ORM\ManyToOne(targetEntity="MediaBundle\Entity\Series", inversedBy="reachmes")
     * @ORM\JoinColumn(name="reachme_id", referencedColumnName="id")
     */
    private $series;

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
     * Set type
     *
     * @param string $type
     * @return Reachme
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set uri
     *
     * @param string $uri
     * @return Reachme
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set series
     *
     * @param \MediaBundle\Entity\Series $series
     * @return Reachme
     */
    public function setSeries(Series $series = null)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Get series
     *
     * @return \MediaBundle\Entity\Series
     */
    public function getSeries()
    {
        return $this->series;
    }
}
