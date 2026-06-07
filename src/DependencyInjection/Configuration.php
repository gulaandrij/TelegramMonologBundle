<?php

declare(strict_types=1);

namespace TelegramMonolog\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('telegram_monolog');
        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('token')->isRequired()->end()
            ->scalarNode('chat_id')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
