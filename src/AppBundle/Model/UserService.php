<?php

namespace AppBundle\Model;

class UserService {

    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function updateSubscription($user, $uniqID)
    {
        $series = $this->em->getRepository('AppBundle:Series')->findOneBy(['uniqID' => $uniqID]);
        $in_abo = false;
        foreach ($user->getChannels() as $abo_channel) {
            if ($abo_channel->getUniqID() == $uniqID) {
                $in_abo = true;
                break;
            }
        }

        if ($in_abo) {
            $user->removeChannel($series);
        } else {
            $user->addChannel($series);
        }
        $this->em->persist($series);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function updateFavorite($user, $uniqID)
    {
        $episode = $this->em->getRepository('AppBundle:Episode')->findOneBy(['uniqID' => $uniqID]);
        $is_favorite = false;
        foreach ($user->getFavorites() as $favorite) {
            if ($favorite->getUniqID() == $uniqID) {
                $is_favorite = true;
                break;
            }
        }

        if ($is_favorite) {
            $user->removeFavorite($episode);
        } else {
            $user->addFavorite($episode);
        }

        $this->em->persist($episode);
        $this->em->persist($user);
        $this->em->flush();
    }
}
