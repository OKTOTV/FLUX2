<?php

namespace AppBundle\Twig;

class EpisodeExtension extends \Twig_Extension
{
    private $repo;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('bestEpisodes', array($this, 'bestEpisodesFunction'))
        );
    }

    public function bestEpisodesFunction($count = 8)
    {
        $episodes = $this->repo->findBestEpisodes($count);
        return $episodes;
    }

    public function newestEpisodesFunction($count = 8)
    {
        $episodes = $this->repo->findNewestEpisodes($count);
        return $episodes;
    }

    public function getName() {
        return 'oktothek_episode_extension';
    }
}
