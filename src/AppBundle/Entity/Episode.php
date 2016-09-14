<?php

namespace AppBundle\Entity;

use Okto\MediaBundle\Entity\Episode as OktoEpisode;
use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\EpisodeRepository")
 */
class Episode extends OktoEpisode {

    /**
     * @JMS\Expose
     * @JMS\Type("array<string>")
     * @JMS\Groups({"search"})
     * @ORM\ManyToMany(targetEntity="Okto\MediaBundle\Entity\TagInterface")
     * @ORM\JoinTable(name="episode_tags",
     *      joinColumns={@ORM\JoinColumn(name="episode_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tags;

    /**
    * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="favorites")
    */
    private $users;

    /**
     * Add users
     *
     * @param \AppBundle\Entity\User $users
     * @return Episode
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add tags
     *
     * @param \AppBundle\Entity\Tag $tags
     * @return Episode
     */
    public function addTag(Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \AppBundle\Entity\Tag $tags
     */
    public function removeTag(Tag $tags)
    {
        $this->tags->removeElement($tags);
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
        return $this;
    }
}
