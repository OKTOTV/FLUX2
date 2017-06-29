<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Series;
use AppBundle\Entity\User;

/**
 * Abonnement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AbonnementRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Abonnement
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
     * @var boolean
     *
     * @ORM\Column(name="livestream", type="boolean")
     */
    private $livestream;

    /**
     * @var boolean
     *
     * @ORM\Column(name="new_episode", type="boolean")
     */
    private $newEpisode;

    /**
     * @var boolean
     *
     * @ORM\Column(name="new_post", type="boolean")
     */
    private $newPost;

    /**
     * @ORM\Column(name="send_mails", type="boolean", options={"default"=false})
     */
    private $send_mails;

    /**
     * @ORM\Column(name="new_comment_on_episode", type="boolean", options={"default"=false})
     */
    private $newCommentOnEpisode;

    /**
     * @ORM\Column(name="new_comment_on_blogpost", type="boolean", options={"default"=false})
     */
    private $newCommentOnBlogPost;

    /**
     * @ORM\Column(name="encodedEpisode", type="boolean", options={"default"=false})
     */
    private $encodedEpisode;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="abonnements")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Series", inversedBy="abonnements")
     * @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     */
    private $series;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->livestream = true;
        $this->newEpisode = true;
        $this->newPost    = false;
        $this->send_mails  = false;
    }

    public function __toString()
    {
        return $this->getSeries()->getName();
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
     * Set livestream
     *
     * @param boolean $livestream
     * @return Abonnement
     */
    public function setLivestream($livestream)
    {
        $this->livestream = $livestream;

        return $this;
    }

    /**
     * Get livestream
     *
     * @return boolean
     */
    public function getLivestream()
    {
        return $this->livestream;
    }

    /**
     * Set newEpisode
     *
     * @param boolean $newEpisode
     * @return Abonnement
     */
    public function setNewEpisode($newEpisode)
    {
        $this->newEpisode = $newEpisode;

        return $this;
    }

    /**
     * Get newEpisode
     *
     * @return boolean
     */
    public function getNewEpisode()
    {
        return $this->newEpisode;
    }

    /**
     * Set newPost
     *
     * @param boolean $newPost
     * @return Abonnement
     */
    public function setNewPost($newPost)
    {
        $this->newPost = $newPost;

        return $this;
    }

    /**
     * Get newPost
     *
     * @return boolean
     */
    public function getNewPost()
    {
        return $this->newPost;
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
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Abonnement
     */
    public function setUser(User $user = null)
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
     * Set series
     *
     * @param \MediaBundle\Entity\Series $series
     * @return Abonnement
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
     * Set send_mails
     *
     * @param boolean $sendMails
     * @return Abonnement
     */
    public function setSendMails($sendMails)
    {
        $this->send_mails = $sendMails;

        return $this;
    }

    /**
     * Get send_mails
     *
     * @return boolean
     */
    public function getSendMails()
    {
        return $this->send_mails;
    }

    /**
     * Set newCommentOnEpisode
     *
     * @param boolean $newCommentOnEpisode
     * @return Abonnement
     */
    public function setNewCommentOnEpisode($newCommentOnEpisode)
    {
        $this->newCommentOnEpisode = $newCommentOnEpisode;

        return $this;
    }

    /**
     * Get newCommentOnEpisode
     *
     * @return boolean 
     */
    public function getNewCommentOnEpisode()
    {
        return $this->newCommentOnEpisode;
    }

    /**
     * Set newCommentOnBlogPost
     *
     * @param boolean $newCommentOnBlogPost
     * @return Abonnement
     */
    public function setNewCommentOnBlogPost($newCommentOnBlogPost)
    {
        $this->newCommentOnBlogPost = $newCommentOnBlogPost;

        return $this;
    }

    /**
     * Get newCommentOnBlogPost
     *
     * @return boolean 
     */
    public function getNewCommentOnBlogPost()
    {
        return $this->newCommentOnBlogPost;
    }

    /**
     * Set encodedEpisode
     *
     * @param boolean $encodedEpisode
     * @return Abonnement
     */
    public function setEncodedEpisode($encodedEpisode)
    {
        $this->encodedEpisode = $encodedEpisode;

        return $this;
    }

    /**
     * Get encodedEpisode
     *
     * @return boolean 
     */
    public function getEncodedEpisode()
    {
        return $this->encodedEpisode;
    }
}
