<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slide
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\SlideRepository")
 */
class Slide
{
    const TYPE_EPISODE = 0;
    const TYPE_NEWS = 1;

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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;

    /**
    * @ORM\OneToOne(targetEntity="Asset")
    * @ORM\JoinColumn(name="asset_id", referencedColumnName="id")
    */
    private $asset;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $link;

    /**
    * @var DateTime
    * @ORM\Column(name="online_at", type="datetime")
    */
    private $onlineAt;

    /**
     * @ORM\Column(name="slide_type", type="integer")
     */
    private $slideType;

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
     * Set name
     *
     * @param string $name
     * @return Slide
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Slide
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Slide
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set onlineAt
     *
     * @param \DateTime $onlineAt
     * @return Slide
     */
    public function setOnlineAt($onlineAt)
    {
        $this->onlineAt = $onlineAt;

        return $this;
    }

    /**
     * Get onlineAt
     *
     * @return \DateTime
     */
    public function getOnlineAt()
    {
        return $this->onlineAt;
    }

    /**
     * Set asset
     *
     * @param \AppBundle\Entity\Asset $asset
     * @return Post
     */
    public function setAsset(\AppBundle\Entity\Asset $asset = null)
    {
        $this->asset = $asset;

        return $this;
    }

    /**
     * Get asset
     *
     * @return \AppBundle\Entity\Asset
     */
    public function getAsset()
    {
        return $this->asset;
    }

    /**
     * Set slideType
     *
     * @param integer $slideType
     * @return Slide
     */
    public function setSlideType($slideType)
    {
        $this->slideType = $slideType;

        return $this;
    }

    /**
     * Get slideType
     *
     * @return integer
     */
    public function getSlideType()
    {
        return $this->slideType;
    }
}
