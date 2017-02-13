<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
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
     * @Assert\NotBlank()
     * @Assert\Length(max="500")
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(name="referer", type="string", length=13)
     */
    private $referer;

    /**
     * @ORM\Column(name="removed", type="boolean", options={"default": false})
     */
    private $removed;

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->removed = false;
    }

    public function setText($text = "")
    {
        $this->text = $text;
        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Category $parent
     * @return Comment
     */
    public function setParent(\AppBundle\Entity\Comment $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \AppBundle\Entity\Comment $children
     * @return Comment
     */
    public function addChild(\AppBundle\Entity\Comment $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \AppBundle\Entity\Comment $children
     */
    public function removeChild(\AppBundle\Entity\Comment $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Comment
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
     * @return Comment
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

    public function getRemoved()
    {
        return $this->removed;
    }

    public function setRemoved($value)
    {
        $this->removed = $value;
        return $this;
    }

    /**
     * small helper for nicer syntax
     */
    public function isRemoved()
    {
        return $this->removed;
    }
}
