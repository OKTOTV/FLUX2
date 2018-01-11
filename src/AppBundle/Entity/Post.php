<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Series;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
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
     * @ORM\Column(name="slug", length=32, type="string", unique=true)
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="_")
     */
    private $slug;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=2500)
     * @ORM\Column(name="description", type="text", length=2600)
     */
    private $description;

   /**
    * @Assert\NotBlank()
    * @Assert\Length(max=230)
    * @ORM\Column(name="teaser", type="string", length=230, nullable=true)
    */
    private $teaser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="pinned", type="boolean", options={"default" = 0})
     */
    private $pinned;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="online_at", type="datetime")
     */
    private $onlineAt;

    /**
     * @ORM\Column(name="uniqID", type="string", length=13)
     */
    private $uniqID;

    /**
     * @ORM\ManyToMany(targetEntity="Okto\MediaBundle\Entity\TagInterface", inversedBy="posts", cascade={"persist"})
     * @ORM\JoinTable(name="post_tag")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity="Asset", inversedBy="posts", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinTable(name="post_asset")
     */
    private $assets;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Series", inversedBy="posts")
     * @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     */
    private $series;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PostComment", mappedBy="post")
     */
    private $comments;

    public function __construct() {
        $this->uniqID = uniqid();
        $this->pinned = false;
        $this->assets = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->onlineAt = new \Datetime();
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
     * Set name
     *
     * @param string $name
     * @return Post
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
     * @return Post
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return Post
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
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
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set onlineAt
     *
     * @param \DateTime $onlineAt
     * @return Post
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
     * Add assets
     *
     * @param \AppBundle\Entity\Asset $assets
     * @return Post
     */
    public function addAsset($asset)
    {
        $this->assets[] = $asset;
        $asset->addPost($this);

        return $this;
    }

    /**
     * Remove assets
     *
     * @param \AppBundle\Entity\Asset $assets
     */
    public function removeAsset($asset)
    {
        $this->assets->removeElement($asset);
        $asset->removePost($this);
    }

    public function setAssets($assets = null)
    {
        $this->assets = $assets;
    }

    /**
     * Get assets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add tags
     *
     * @param \AppBundle\Entity\Tag $tag
     * @return Post
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;
        $tag->addPost($this);

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \AppBundle\Entity\Tag $tags
     */
    public function removeTag($tag)
    {
        $this->tags->removeElement($tag);
        $tag->removePost($this);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Set uniqID
     *
     * @param string $uniqID
     * @return Post
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
     * Set pinned
     *
     * @param boolean $pinned
     * @return Post
     */
    public function setPinned($pinned)
    {
        $this->pinned = $pinned;

        return $this;
    }

    /**
     * Get pinned
     *
     * @return boolean
     */
    public function getPinned()
    {
        return $this->pinned;
    }

    /**
     * Set teaser
     *
     * @param string $teaser
     * @return Post
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;

        return $this;
    }

    /**
     * Get teaser
     *
     * @return string
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * Set series
     *
     * @param \MediaBundle\Entity\Series $series
     * @return Post
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

    /**
     * Add comments
     *
     * @param \AppBundle\Entity\PostComment $comment
     * @return Post
     */
    public function addComment(\AppBundle\Entity\PostComment $comment)
    {
        $this->comments[] = $comment;
        $comment->setPost($this);
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \AppBundle\Entity\PostComment $comment
     */
    public function removeComment(\AppBundle\Entity\PostComment $comment)
    {
        $this->comments->removeElement($comment);
        $comment->setPost(null);
        return $this;
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
