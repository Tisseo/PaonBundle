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
                    ->arrayNode('jenkins_users')
                        ->prototype('array')
                            ->info('Configuration des utilisateurs pour imports/exports')
                            ->children()
                                ->scalarNode('profile')->defaultValue('admin')->isRequired()->end()
                                ->scalarNode('user')->isRequired()->end()
                            ->end()
                            ->children()
                                ->scalarNode('profile')->defaultValue('iv')->isRequired()->end()
                                ->scalarNode('user')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
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
                    ->arrayNode('default_email_dest')
                        ->isRequired()
                        ->prototype('scalar')->end()
                    ->end()
                    ->arrayNode('line_validation_default_email_dest')
                        ->isRequired()
                        ->prototype('scalar')->end()
                    ->end()
                    ->scalarNode('default_email_exp')->isRequired()->end()
                ->end()
            ->end()
            ->arrayNode('referential_datasources')
                ->info('Referential datasource used to retrieve a list of importable offers')
                ->defaultValue(array('hastus'))
                ->requiresAtLeastOneElement()
                ->prototype('scalar')->end()
            ->end()
            ->scalarNode('schematics_directory')->defaultValue('%kernel.root_dir%/../web/uploads/schematics')->cannotBeEmpty()->end()
            ->scalarNode('schematics_relative_directory')->defaultValue('uploads/schematics')->cannotBeEmpty()->end()
        ->end();

        return $treeBuilder;
    }
}
