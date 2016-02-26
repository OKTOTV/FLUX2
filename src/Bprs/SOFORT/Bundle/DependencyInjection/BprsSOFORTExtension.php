<?php

namespace Bprs\SOFORT\Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BprsSOFORTExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $container->setParameter('bprs_sofort.configkey', sprintf('%s:%s:%s', $config['customer_id'], $config['project_id'], $config['api_id']));
        $container->setParameter('bprs_sofort.currency_code', $config['currency_code']);
        $container->setParameter('bprs_sofort.success_url', $config['success_url']);
        $container->setParameter('bprs_sofort.abort_url', $config['abort_url']);
        $container->setParameter('bprs_sofort.notification_url', $config['notification_url']);
    }
}
