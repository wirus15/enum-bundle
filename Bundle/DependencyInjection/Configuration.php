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
                    ->enumNode('type_storage')
                        ->values(['file', 'memory'])
                        ->defaultValue('file')
                    ->end()
                    ->arrayNode('types')
                        ->prototype('scalar')
                    ->end()
                ->end();

        return $treeBuilder;
    }
}
