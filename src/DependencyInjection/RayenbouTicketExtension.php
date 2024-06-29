<?php

namespace Rayenbou\TicketBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class RayenbouTicketExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('rayenbou_ticket.authentication.url', $config['authentication']['url']);
        $container->setParameter('rayenbou_ticket.authentication.username', $config['authentication']['username']);
        $container->setParameter('rayenbou_ticket.authentication.password', $config['authentication']['password']);
        $container->setParameter('rayenbou_ticket.settings.verify_peer', $config['settings']['verify_peer']);
    }
}