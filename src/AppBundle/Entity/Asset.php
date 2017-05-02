<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bprs\AssetBundle\Entity\Asset as BaseAsset;

/**
 * Asset
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\AssetRepository")
 */
class Asset extends BaseAsset
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="files")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Series", inversedBy="files")
     * @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     */
    protected $series;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Post", mappedBy="assets")
     */
    protected $posts;

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($user)
    {
        $this->owner = $user;
        return $this;
    }

    public function getSeries()
    {
        return $this->series;
    }

    public function setSeries($series)
    {
        $this->series = $series;
        return $this;
    }

    public function addPost($post)
    {
        $this->posts[] = $post;
        return $this;
    }

    public function removePost($post)
    {
        $this->posts->removeElement($post);
        return $this;
    }

    public function getPosts()
    {
        return $this->posts;
    }

    public function setPosts($posts)
    {
        $this->posts = $posts;
        return $this;
    }
}
