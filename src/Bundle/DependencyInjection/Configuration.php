<?php

namespace Enum\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('enum');

        $rootNode
            ->children()
                ->arrayNode('doctrine')
                    ->children()
                        ->enumNode('type_storage')
                            ->info('The way enum doctrine types are stored. Available options are: file, memory.')
                            ->values(['file', 'memory'])
                            ->defaultValue('file')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('enums')
                    ->useAttributeAsKey('key')
                    ->arrayPrototype()
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function ($v) {
                                return ['class' => $v];
                            })
                        ->end()
                        ->children()
                            ->scalarNode('class')
                                ->info('Enum class (FQCN)')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('doctrine_type')
                                ->info('[optional] Doctrine type name for use in mapping definition.')
                                ->defaultNull()
                            ->end()
                            ->scalarNode('translation_prefix')
                                ->info('[optional] Translation prefix for use in translation files.')
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
