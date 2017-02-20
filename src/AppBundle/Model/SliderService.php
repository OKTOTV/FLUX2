<?php

namespace AppBundle\Model;

use AppBundle\Entity\Slide;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SliderService
{

    private $asset_helper;
    private $em;
    private $router;

    public function __construct($asset_helper, $em, $router)
    {
        $this->asset_helper = $asset_helper;
        $this->em = $em;
        $this->router = $router;
    }

    function deleteSlide($slide)
    {
        if ($slide->getAsset()) {
            $this->asset_helper->deleteAsset($slide->getAsset());
        }
        $this->em->remove($slide);
        $this->em->flush();
    }

    public function createSlideFromEpisode($episode)
    {
        $slide = new Slide();
        $slide->setAsset($episode->getPosterframe());
        $slide->setName($episode->getName().' - '.$episode->getSeries()->getName());
        $slide->setOnlineAt(new \DateTime());
        $slide->setLink($this->router->generate('oktothek_show_episode', ['uniqID' => $episode->getUniqID()], UrlGeneratorInterface::ABSOLUTE_URL));
        $slide->setSlideType(Slide::TYPE_EPISODE);
        return $slide;
    }

    public function createSlideFromNews($news)
    {
        $slide = new Slide();
        $slide->setName($news->getName());
        $slide->setOnlineAt(new \DateTime());
        $slide->setLink($this->router->generate('oktothek_show_news', ['slug' => $news->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL));
        $slide->setSlideType(Slide::TYPE_NEWS);
        return $slide;
    }
}
?>
