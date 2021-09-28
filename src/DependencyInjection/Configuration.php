<?php

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('spinbits_sylius_baselinker_plugin');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
