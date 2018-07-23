<?php

namespace TelegramMonologBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
        $rootNode = $treeBuilder->root('monolog_telegram');
        $rootNode
            ->children()
            ->scalarNode('token')->isRequired()->end()
            ->scalarNode('chat_id')->isRequired()->end()
//            ->scalarNode('level')->isRequired()->end()
//            ->scalarNode('bubble')->isRequired()->end()
            ->end();
        return $treeBuilder;
    }
}
