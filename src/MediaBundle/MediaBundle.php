<?php

namespace MediaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use MediaBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;

class MediaBundle extends Bundle
{
    public function getParent()
    {
        return 'OktolabMediaBundle';
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }
}
