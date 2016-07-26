<?php

namespace Bprs\AnalyticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Info
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Info {

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
     * @ORM\Column(name="info_key", type="string", length=23)
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="info_value", type="string", length=23)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Logstate", inversedBy="values")
     * @ORM\JoinColumn(name="logstate_id", referencedColumnName="id")
     */
    private $logstate;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Set logstate
     *
     * @param \Bprs\AnalyticsBundle\Entity\Logstate $logstate
     * @return Info
     */
    public function setLogstate(\Bprs\AnalyticsBundle\Entity\Logstate $logstate = null)
    {
        $this->logstate = $logstate;

        return $this;
    }

    /**
     * Get logstate
     *
     * @return \Bprs\AnalyticsBundle\Entity\Logstate
     */
    public function getLogstate()
    {
        return $this->logstate;
    }
}
