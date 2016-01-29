<?php

namespace MediaBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class AdvancedLoader extends Loader
{
    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        $resource = __DIR__.'/../../../vendor/oktolab/media-bundle/Oktolab/MediaBundle/Resources/config/routing.yml';
        $type = 'yaml';

        $importedRoutes = $this->import($resource, $type);

        $resource_overwrite = '@OktolabMediaBundle/Resources/config/routing.yml';

        $overwrittenRoutes = $this->import($resource_overwrite, $type);

        $collection->addCollection($importedRoutes);
        $collection->addCollection($overwrittenRoutes);

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return $type === 'oktolab_media';
    }
}
