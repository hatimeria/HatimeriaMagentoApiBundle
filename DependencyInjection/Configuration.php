<?php

namespace Hatimeria\MagentoApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hatimeria_magentoapi');

        $rootNode
            ->children()
                ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('user')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('key')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('route')->defaultValue('/api/soap/?wsdl')->end()
                ->arrayNode('defaults')
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')
                ->end()
            ->end();

        return $treeBuilder;
    }
    
}
