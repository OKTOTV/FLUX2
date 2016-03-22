<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Playlist
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PlaylistRepository")
 * @JMS\ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 * JMS\AccessType("public_method")
 */
class Playlist
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\ReadOnly
     */
    private $id;

    /**
     * @var string
     * @JMS\Expose
     * @JMS\Type("string")
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
    * @var string
    * @JMS\Expose
    * @JMS\Type("string")
    * @ORM\Column(name="uniqID", type="string", length=13)
    */
    private $uniqID;

    /**
     * @var string
     * @JMS\Expose
     * @JMS\Type("string")
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     * @JMS\Expose
     * @JMS\Type("DateTime")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @JMS\Expose
     * @JMS\Type("DateTime")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
    * @ORM\ManyToOne(targetEntity="User", inversedBy="playlists")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;

    /**
    * @ORM\OneToMany(targetEntity="Playlistitem", mappedBy="playlist")
    */
    private $items;

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
     * @return Playlist
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
     * @return Playlist
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
     * Set createdAt
     * @ORM\PrePersist
     * @param \DateTime $createdAt
     * @return Episode
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @param \DateTime $updatedAt
     * @return Episode
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uniqID = uniqid();
    }

    /**
     * Set uniqID
     *
     * @param string $uniqID
     * @return Playlist
     */
    public function setUniqID($uniqID)
    {
        $this->uniqID = $uniqID;

        return $this;
    }

    /**
     * Get uniqID
     *
     * @return string
     */
    public function getUniqID()
    {
        return $this->uniqID;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Playlist
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add items
     *
     * @param \AppBundle\Entity\Playlistitem $items
     * @return Playlist
     */
    public function addItem(\AppBundle\Entity\Playlistitem $items)
    {
        $this->items[] = $items;
        $items->setPlaylist($this);
        return $this;
    }

    /**
     * Remove items
     *
     * @param \AppBundle\Entity\Playlistitem $items
     */
    public function removeItem(\AppBundle\Entity\Playlistitem $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}
