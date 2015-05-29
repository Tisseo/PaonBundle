<?php

namespace Tisseo\PaonBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('tisseo_paon', 'array');
        $rootNode->children()
            ->arrayNode('data_exchange')
                ->info('Configuration des imports/exports')
                ->children()
                    ->scalarNode('jenkins_server')->isRequired()->end()
                    ->scalarNode('jenkins_user')->isRequired()->end()
                    ->arrayNode('jobs')
                        ->children()
                            ->scalarNode('master_prefix')->isRequired()->end()
                            ->scalarNode('atomic_prefix')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('mailer')
                ->info('Configuration des envois de mail')
                ->children()
                    ->scalarNode('default_email_dest')->isRequired()->end()
                    ->scalarNode('default_email_exp')->isRequired()->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
