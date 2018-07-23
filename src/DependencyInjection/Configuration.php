<?php

namespace TelegramMonologBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

/**
 * Class Configuration
 *
 * @package MSComputerVisionBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('monolog_telegram')
//            ->children()
//            ->scalarNode('token')->isRequired()->end()
////            ->scalarNode('chat_id')->isRequired()->end()
//////            ->scalarNode('level')->isRequired()->end()
//////            ->scalarNode('bubble')->isRequired()->end()
//            ->end()
->end();
        return $treeBuilder;
    }
}
