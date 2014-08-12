<?php

namespace Appsco\Dashboard\ApiBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AppscoDashboardApiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

}