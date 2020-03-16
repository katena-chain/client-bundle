<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\ClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const BUNDLE_KEY = "kc_client";
    const CHAIN_KEY = "chain";
    const API_KEY = "api";

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        if (method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder(self::BUNDLE_KEY);
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root(self::BUNDLE_KEY);
        }

        $rootNode
            ->children()
                ->arrayNode(self::CHAIN_KEY)
                    ->isRequired()
                    ->children()
                    ->scalarNode("company_bcid")->defaultValue("")->end()
                    ->scalarNode("chain_id")->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode("private_key")->defaultValue("")->end()
                ->end()
            ->end()
                ->arrayNode(self::API_KEY)
                    ->isRequired()
                    ->children()
                    ->scalarNode("url")->isRequired()->cannotBeEmpty()->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
