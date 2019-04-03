<?php

declare(strict_types=1);

namespace StfalconStudio\SwaggerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('swagger')
            ->children()
                ->scalarNode('config_folder')->cannotBeEmpty()->isRequired()->end()
        ;

        return $treeBuilder;
    }
}