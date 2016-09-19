<?php

namespace AppBundle\Entity;

use Okto\MediaBundle\Entity\Tag as OktoTag;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\TagRepository")
 */
class Tag extends OktoTag {

    /**
     * @ORM\Column(name="rank", type="integer", nullable=true)
     */
    private $rank;

    /**
     * @ORM\Column(name="highlight", type="boolean", options={"default" = 0})
     */
    private $highlight;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Post", mappedBy="tags")
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Page", mappedBy="tags")
     */
    private $pages;

    /**
     * Set rank
     *
     * @param integer $rank
     * @return Tag
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set highlight
     *
     * @param boolean $highlight
     * @return Tag
     */
    public function setHighlight($highlight)
    {
        $this->highlight = $highlight;

        return $this;
    }

    /**
     * Get highlight
     *
     * @return boolean
     */
    public function getHighlight()
    {
        return $this->highlight;
    }

    public function getPosts()
    {
        return $this->posts;

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

    public function setPosts($posts)
    {
        $this->posts = $posts;
        return $this;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function addPage($page)
    {
        $this->pages[] = $page;
        return $this;
    }

    public function removePage($page)
    {
        $this->pages->removeElement($page);
        return $this;
    }

    public function setPages($pages)
    {
        $this->pages = $pages;
        return $this;
    }
}
