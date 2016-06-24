<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="send_mails", type="boolean", options={"default"= false})
     */
    private $send_mails;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="abonnements")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="MediaBundle\Entity\Series", inversedBy="abonnements")
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
     * Set series
     *
     * @param \MediaBundle\Entity\Series $series
     * @return Abonnement
     */
    public function setSeries(\MediaBundle\Entity\Series $series = null)
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
}
