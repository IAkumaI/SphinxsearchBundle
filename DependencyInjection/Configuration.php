<?php

namespace IAkumaI\SphinxsearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;


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
        $rootNode = $treeBuilder->root('sphinxsearch');

        $rootNode
            ->children()
                ->arrayNode('searchd')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')->defaultValue('localhost')->end()
                        ->scalarNode('port')->defaultValue('9312')->end()
                        ->scalarNode('socket')->defaultNull()->end()
                    ->end()
                ->end()
            ->end();

        $rootNode
            ->children()
                ->arrayNode('sphinx_api')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('file')->defaultValue(__DIR__.'../../Sphinx/SphinxAPI.php')->end()
                    ->end()
                ->end()
            ->end();

        $rootNode
            ->children()
                ->arrayNode('indexes')
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
