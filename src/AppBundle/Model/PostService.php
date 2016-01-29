<?php

namespace AppBundle\Model;

use AppBundle\Entity\Slides;

class PostService
{
    private $asset_helper;
    private $em;

    public function __construct($em, $asset_helper) {
        $this->em = $em;
        $this->asset_helper = $asset_helper;
    }

    public function deletePost($post)
    {
        $this->em->remove($post);
        if (count($post->getAssets())) {
            foreach($post->getAssets() as $asset) {
                $this->asset_helper->deleteAsset($asset);
            }
        }
        $this->em->flush();
    }
}
