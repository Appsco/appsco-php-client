<?php

namespace Appsco\Dashboard\ApiBundle\DependencyInjection;

use Appsco\Dashboard\ApiBundle\Client\AppscoClient;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('appsco_accounts_api');

        $root->children()
            ->scalarNode('scheme')->defaultValue('https')->cannotBeEmpty()->end()
            ->scalarNode('domain')->defaultValue('accounts.dev.appsco.com')->cannotBeEmpty()->end()
            ->scalarNode('sufix')->defaultValue('')->end()
            ->scalarNode('default_redirect_uri')->defaultValue('')->end()
            ->scalarNode('client_id')->defaultValue('')->end()
            ->scalarNode('client_secret')->defaultValue('')->end()
            ->scalarNode('ca_path')->defaultValue('/usr/lib/ssl/certs')->end()
            ->booleanNode('loose_ssl')->defaultValue(false)->end()
            ->scalarNode('auth_type')->defaultValue(AppscoClient::AUTH_TYPE_ACCESS_TOKEN)->end()
            ->end();

        return $treeBuilder;
    }

}