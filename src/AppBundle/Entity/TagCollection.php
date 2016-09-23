<?php

namespace AppBundle\Entity;

use \Doctrine\Common\Collections\ArrayCollection;

class TagCollection
{
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function addTag($tag)
    {
        $this->tags[] = $tag;
        return $this;
    }

    public function removeTag($tag)
    {
        $this->tags->removeElement($tag);
        return $this;
    }

    public function setTags($tags)
    {
        foreach($tags as $tag) {
            $this->addTag($tag);
        }
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }
}
