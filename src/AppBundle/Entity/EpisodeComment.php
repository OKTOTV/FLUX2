<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Comment as BaseComment;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\EpisodeCommentRepository")
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 */
class EpisodeComment extends BaseComment {

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Episode", inversedBy="comments")
     * @ORM\JoinColumn(name="episode_id", referencedColumnName="id")
     */
    protected $episode;

    /**
     * One Comment has Many Answers.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EpisodeComment", mappedBy="parent", cascade={"persist", "remove"})
     */
    protected $children;

    /**
     * Many Answers have One Parent Comment.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EpisodeComment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Bprs\UserBundle\Entity\UserInterface", inversedBy="episode_comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * Set episode
     *
     * @param \AppBundle\Entity\Episode $episode
     * @return EpisodeComment
     */
    public function setEpisode(\AppBundle\Entity\Episode $episode = null)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return \AppBundle\Entity\Episode
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Category $parent
     * @return Comment
     */
    public function setParent(EpisodeComment $parent = null)
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
    public function addChild(EpisodeComment $child)
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
    public function removeChild(EpisodeComment $child)
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
        return $this->episode;
    }
}
