<?php

namespace Bprs\SOFORT\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bprs_sofort');
        $rootNode
            ->children()
                ->scalarNode('customer_id')->isRequired()->end()
                ->scalarNode('project_id')->isRequired()->end()
                ->scalarNode('api_id')->isRequired()->end()
                ->scalarNode('currency_code')->defaultValue('EUR')->end()
                ->scalarNode('success_url')->defaultValue('')->end()
                ->scalarNode('abort_url')->defaultValue('')->end()
                ->scalarNode('notification_url')->defaultValue('')->end()
            ->end();
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
