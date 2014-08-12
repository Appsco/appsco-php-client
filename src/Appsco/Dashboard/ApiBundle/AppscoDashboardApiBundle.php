<?php

namespace Appsco\Dashboard\ApiBundle;

use Appsco\Dashboard\ApiBundle\DependencyInjection\Security\Factory\AppscoFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppscoDashboardApiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var \Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension $extension */
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new AppscoFactory());
    }

}