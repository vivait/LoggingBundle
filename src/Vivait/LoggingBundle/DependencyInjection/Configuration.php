<?php

namespace Vivait\LoggingBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('vivait_logging');

        $rootNode
            ->children()
                ->scalarNode('application_name')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
