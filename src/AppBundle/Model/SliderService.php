<?php

namespace AppBundle\Model;

use AppBundle\Entity\Slides;

class SliderService
{

    private $episode_repository;
    private $slide_repository;
    private $router;

    public function __construct($episode_repository, $slide_repository, $router)
    {
        $this->episode_repository = $episode_repository;
        $this->slide_repository = $slide_repository;
        $this->router = $router;
    }


    public function getSlides($numberSlides = 5)
    {
        $slides = $this->slide_repository->findNewestSlides(3);
        $numberSlides =- count($slides);
        $new_episodes = $this->episode_repository->findNewestEpisodes(floor($numberSlides/2));
        $fav_episodes = $this->episode_repository->find
    }

    private function addEpisodes($episodes, $slides)
    {
        foreach($episode in $episodes) {
            $slide = new Slide();
            $slide->setName() = $episode->getName();
            $slide->setLink() = $this->router->generate(
                'oktothek_show_episode', array('uniqID' => $episode->getUniqID())
            );
            $slides->add($slide);
        }
    }
}
?>
