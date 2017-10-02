<?php

namespace AppBundle\Twig;

class UserExtension extends \Twig_Extension
{
    private $repo;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('newestEpisodesInAbonnement', [$this, 'newestEpisodesInAbonnementFunction']),
            new \Twig_SimpleFunction('newestPostsInAbonnement', [$this, 'newestPostsInAbonnementFunction'])
        ];
    }

    public function newestEpisodesInAbonnementFunction($user, $count = 8)
    {
        return $this->repo->findNewestEpisodesInUserAbonnements($user, $count);
    }

    public function newestPostsInAbonnementFunction($user, $count = 4)
    {
        return $this->repo->findNewestPostsInUserAbonnements($user, $count);
    }

    public function getName() {
        return 'oktothek_episode_extension';
    }
}
