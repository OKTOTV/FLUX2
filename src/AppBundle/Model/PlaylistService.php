<?php

namespace AppBundle\Model;

use AppBundle\Entity\Playlist;
use AppBundle\Entity\Playlistitem;

class PlaylistService
{
    private $em;

    public function __construct($em) {
        $this->em = $em;
    }

    /**
     * adds episode to users playlist (if not already in playlist)
     */
    public function addToPlaylist($episodeID, $playlistID, $user)
    {
        foreach ($user->getPlaylists() as $playlist) {
            if ($playlist->getUniqID() == $playlistID) {
                $NotInPlaylist = true;
                foreach ($playlist->getItems() as $item) {
                    if ($item->getEpisode()->getUniqID() == $episodeID) {
                        $NotInPlaylist = false;
                    }
                }
                $episode = $this->em->getRepository('AppBundle:Episode')->findOneBy(['uniqID' => $episodeID]);
                $playlistItem = new PlaylistItem();
                $playlistItem->setEpisode($episode);
                $playlist->addItem($playlistItem);
                $playlistItem->setSortnumber(count($playlist->getItems())+1);
                $this->em->persist($playlist);
                $this->em->persist($playlistItem);
                $this->em->flush();
                break;
            }
        }
    }

    /**
     * removes episode from users playlist
     */
    public function removeFromPlaylist($episodeID, $playlistID, $user)
    {
        foreach ($user->getPlaylists() as $playlist) {
            if ($playlist->getUniqID() == $playlistID) {
                foreach ($playlist->getItems() as $playlistItem) {
                    if ($playlistItem->getEpisode()->getUniqID() == $episodeID) {
                        $playlist->removeItem($playlistItem);
                        $playlistItem->setPlaylist(null);
                        $this->em->remove($playlistItem);
                        $this->em->persist($playlist);
                        $this->em->flush();
                    }
                }
            }
        }
    }

    /**
     * creates a new playlist for user and adds episode 
     */
    public function newPlaylist($name, $user, $uniqID)
    {
        $playlist = new Playlist();
        $playlist->setUser($user);
        $playlist->setName($name);
        $episode = $this->em->getRepository('AppBundle:Episode')->findOneBy(['uniqID' => $uniqID]);
        $playlistItem = new PlaylistItem();
        $playlistItem->setEpisode($episode);
        $playlist->addItem($playlistItem);
        $this->em->persist($playlist);
        $this->em->persist($playlistItem);
        $this->em->flush();
    }
}
