<?php

namespace Bprs\AnalyticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Logstate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bprs\AnalyticsBundle\Entity\LogstateRepository")
 */
class Logstate
{

    const BPRS_AN_REFERER   = "referer";
    const BPRS_AN_URL       = "url";
    const BPRS_AN_UNIQID    = "uniqID";
    const BPRS_AN_TIMESTAMP = "timestamp";
    const BPRS_AN_USERAGENT = "user_agent";
    const BPRS_AN_CLIENTIP  = "client_ip";
    const BPRS_AN_VALUES    = "info_values";

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
     * @ORM\Column(name="referer", type="string", length=2000, nullable=true)
     */
    private $referer;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=2000)
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="string", length=255)
     */
    private $userAgent;

    /**
     * @var string
     *
     * @ORM\Column(name="client_ip", type="string", length=40)
     */
    private $clientIp;

    /**
     * @ORM\OneToMany(targetEntity="Info", mappedBy="logstate")
     */
    private $values;

    public function __construct()
    {
        $this->timestamp = new \Datetime();
    }

    public function __toString()
    {
        return $this->url.' '.$this->Id;
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

    /**
     * Set referer
     *
     * @param string $referer
     * @return Logstate
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * Get referer
     *
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Logstate
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return Logstate
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     * @return Logstate
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set clientIp
     *
     * @param string $clientIp
     * @return Logstate
     */
    public function setClientIp($clientIp)
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    /**
     * Get clientIp
     *
     * @return string
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * Add values
     *
     * @param \Bprs\AnalyticsBundle\Entity\Info $values
     * @return Logstate
     */
    public function addValue(\Bprs\AnalyticsBundle\Entity\Info $values)
    {
        $this->values[] = $values;
        $values->setLogstate($this);
        return $this;
    }

    /**
     * Remove values
     *
     * @param \Bprs\AnalyticsBundle\Entity\Info $values
     */
    public function removeValue(\Bprs\AnalyticsBundle\Entity\Info $values)
    {
        $this->values->removeElement($values);
    }

    /**
     * Get values
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValues()
    {
        return $this->values;
    }
}
