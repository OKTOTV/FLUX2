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
}
