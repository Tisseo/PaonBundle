<?php

namespace Tisseo\PaonBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class TisseoPaonExtension extends Extension
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
        $loader->load('permissions.yml');

        $container->setParameter('tisseo_paon.default_email_dest', $config['mailer']['default_email_dest']);
        $container->setParameter('tisseo_paon.default_email_exp', $config['mailer']['default_email_exp']);

        # DataExchange configuration
        $container->setParameter('tisseo_paon.jenkins_server', $config['data_exchange']['jenkins_server']);
        $container->setParameter('tisseo_paon.jenkins_user', $config['data_exchange']['jenkins_user']);
        $container->setParameter('tisseo_paon.master_job_prefix', $config['data_exchange']['jobs']['master_prefix']);
        $container->setParameter('tisseo_paon.atomic_job_prefix', $config['data_exchange']['jobs']['atomic_prefix']);

    }
}
