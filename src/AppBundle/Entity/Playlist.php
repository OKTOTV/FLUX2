<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oktolab\MediaBundle\Entity\Playlist as BasePlaylist;
use Oktolab\MediaBundle\Entity\PlaylistInterface;
use AppBundle\Entity\Series;

/**
 * Playlist
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PlaylistRepository")
 */
class Playlist extends BasePlaylist implements PlaylistInterface
{
    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="playlists")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;

    /**
    * @ORM\OneToMany(targetEntity="Oktolab\MediaBundle\Entity\Playlistitem", mappedBy="playlist", cascade={"persist", "remove"})
    * @ORM\OrderBy({"sortnumber" = "ASC"})
    */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="Oktolab\MediaBundle\Entity\SeriesInterface", inversedBy="playlists")
     * @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     */
    private $series;

    /**
     * @ORM\Column(name="views", type="integer", options={"defaults" = 0}, nullable=true)
     */
    private $views;

    public function __construct()
    {
        parent::__construct();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
    public function removeItem($item)
    {
        $this->items->removeElement($item);
        $item->setPlaylist(null);
        return $this;
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

    /**
     * Add series
     *
     * @param \MediaBundle\Entity\Series $series
     * @return Playlist
     */
    public function setSeries($series)
    {
        $this->series = $series;
        return $this;
    }

    /**
     * Get series
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeries()
    {
        return $this->series;
    }

    public function getViews()
    {
        return $this->views;
    }

    public function setViews($views)
    {
        $this->views = $views;
        return $this;
    }
}
 ?>
