<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\ClientBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class KCClientExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.yaml');

        $configKeys = [
            Configuration::CHAIN_KEY => [
                "chain_id",
                "company_chain_id",
                "private_key"
            ],
            Configuration::API_KEY => [
                "url",
                "url_suffix"
            ]
        ];

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($configKeys as $configNS => $keys) {
            foreach ($keys as $key) {
                $container->setParameter(vsprintf("%s.%s.%s", [$this->getAlias(), $configNS, $key]), $config[$configNS][$key]);
            }
        }
    }
}
