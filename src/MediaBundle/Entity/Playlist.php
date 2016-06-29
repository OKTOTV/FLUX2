<?php

namespace MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oktolab\MediaBundle\Entity\Playlist as BasePlaylist;
use Oktolab\MediaBundle\Entity\PlaylistInterface;

/**
 * Playlist
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MediaBundle\Entity\Repository\PlaylistRepository")
 */
class Playlist extends BasePlaylist implements PlaylistInterface
{
    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="playlists")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;

    /**
    * @ORM\OneToMany(targetEntity="Oktolab\MediaBundle\Entity\Playlistitem", mappedBy="playlist")
    */
    private $items;

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Playlist
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
     * Add items
     *
     * @param \AppBundle\Entity\Playlistitem $items
     * @return Playlist
     */
    public function addItem($items)
    {
        $this->items[] = $items;
        $items->setPlaylist($this);
        return $this;
    }

    /**
     * Remove items
     *
     * @param \AppBundle\Entity\Playlistitem $items
     */
    public function removeItem($items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}
 ?>
