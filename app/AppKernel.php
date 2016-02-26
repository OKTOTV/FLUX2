<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Bprs\StyleBundle\BprsStyleBundle(),
            new Bprs\AssetBundle\BprsAssetBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new Oneup\FlysystemBundle\OneupFlysystemBundle(),
            //new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Oktolab\MediaBundle\OktolabMediaBundle(),
            new AppBundle\AppBundle(),
            new MediaBundle\MediaBundle(),
            new Bprs\CommandLineBundle\BprsCommandLineBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            // new EightPoints\Bundle\GuzzleBundle\GuzzleBundle(),
            new Bprs\AppLinkBundle\BprsAppLinkBundle(),
            new Bprs\UserBundle\BprsUserBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new Bprs\SOFORT\Bundle\BprsSOFORTBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
