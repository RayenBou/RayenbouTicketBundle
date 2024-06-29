<?php

namespace Rayenbou\TicketBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rayenbou_ticket');

        $treeBuilder->getRootNode()
            ->children()
            ->arrayNode('authentication')
            ->children()
            ->scalarNode('url')->end()
            ->scalarNode('username')->end()
            ->scalarNode('password')->end()
            ->end()
            ->end()
            ->arrayNode('settings')
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('verify_peer')->defaultTrue()->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
