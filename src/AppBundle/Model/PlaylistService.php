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
            if ($playlistID == $playlist->getUniqID()) { // this is users playlist
                $NotInPlaylist = true;
                foreach ($playlist->getItems() as $playlistItem) { // search
                    if ($playlistItem->getEpisode()->getUniqID() == $episodeID) {
                        // episode already in playlist
                        $NotInPlaylist = false;
                    }
                }

                if ($NotInPlaylist) {
                    $episode = $this->em->getRepository('AppBundle:Episode')->findOneBy(['uniqID' => $episodeID]);
                    $playlist = $this->em->getRepository('AppBundle:Playlist')->findOneBy(['uniqID' => $playlistID]);
                    $playlistItem = new PlaylistItem();
                    $playlistItem->setEpisode($episode);
                    $playlistItem->setPlaylist($playlist);
                    $playlist->addItem($playlistItem);
                    $playlistItem->setSortnumber(count($playlist->getItems())+1);

                    $this->em->persist($playlistItem);
                    $this->em->persist($playlist);

                    $this->em->flush();
                }
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
}
