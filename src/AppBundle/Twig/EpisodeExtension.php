<?php

namespace AppBundle\Twig;

class EpisodeExtension extends \Twig_Extension
{
    private $repo;
    private $trans;

    public function __construct($repo, $trans)
    {
        $this->repo = $repo;
        $this->translator = $trans;
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('bestEpisodes', [$this, 'bestEpisodesFunction']),
            new \Twig_SimpleFunction('newestEpisodes', [$this, 'newestEpisodesFunction'])
        ];
    }

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('viewOptimizer', [$this, 'viewOptimizerFilter'])
        ];
    }

    public function bestEpisodesFunction($count = 8)
    {
        $episodes = $this->repo->findTrendingEpisodes($count);//findBestEpisodes($count);
        return $episodes;
    }

    public function newestEpisodesFunction($count = 8)
    {
        $episodes = $this->repo->findNewestEpisodes($count);
        return $episodes;
    }

    /**
     * renders numbers above 999 to 1K, 1.2K, etc
     * if we ever need 1M, you need to change this function. Written 09.11.2017 RS
     */
    public function viewOptimizerFilter($views)
    {
        if ($views > 999) {
            return $this->translator->trans(
                'oktothek.episode_views_thousand',
                ['%views%' => round($views/1000, 1)]
            );
        }
        return $views;
    }

    public function getName() {
        return 'oktothek_episode_extension';
    }
}
