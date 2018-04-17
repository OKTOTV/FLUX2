<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Comment as BaseComment;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PostCommentRepository")
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 */
class PostComment extends BaseComment {

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $post;

    /**
     * One Comment has Many Answers.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PostComment", mappedBy="parent", cascade={"persist", "remove"})
     */
    protected $children;

    /**
     * Many Answers have One Parent Comment.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PostComment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Bprs\UserBundle\Entity\UserInterface", inversedBy="post_comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * Set post
     *
     * @param \AppBundle\Entity\Post $post
     * @return PostComment
     */
    public function setPost(\AppBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \AppBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Category $parent
     * @return Comment
     */
    public function setParent(PostComment $parent = null)
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
     * @param \Bprs\UserBundle\Entity\Comment $children
     * @return Comment
     */
    public function addChild(PostComment $child)
    {
        $this->children[] = $child;
        $child->setParent($this);
        return $this;
    }

    /**
     * Remove children
     *
     * @param \Bprs\UserBundle\Entity\Comment $children
     */
    public function removeChild(PostComment $child)
    {
        $this->children->removeElement($child);
        $child->setParent(null);
        return $this;
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

    public function getCommentedObject() {
        return $this->post;
    }
}
