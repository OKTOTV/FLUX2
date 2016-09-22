<?php

namespace AppBundle\Model;

use AppBundle\Entity\Abonnement;

class UserService {

    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function updateSubscription($user, $uniqID)
    {
        $user_abonnement = null;
        $in_abo = false;
        foreach ($user->getAbonnements() as $abonnement) {
            if ($abonnement->getSeries()->getUniqID() == $uniqID) {
                $in_abo = true;
                $user_abonnement = $abonnement;
                break;
            }
        }

        if ($in_abo) {
            $this->em->remove($user_abonnement);
        } else {
            $series = $this->em->getRepository('AppBundle:Series')->findOneBy(['uniqID' => $uniqID]);
            $user_abonnement = new Abonnement();
            $user_abonnement->setUser($user);
            $user_abonnement->setSeries($series);
            $this->em->persist($user_abonnement);
            $this->em->persist($series);
        }
        $this->em->persist($user);
        $this->em->flush();
    }

    // TODO: user->removeFavorite($episode);
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
