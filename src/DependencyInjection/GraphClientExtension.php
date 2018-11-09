<?php

namespace GraphClientBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class GraphClientExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');
        $config = $this->parseConfig($configs);

        $container->getDefinition('graph_client_bundle.model')
            ->setArgument(0, $config['api']['host'])
            ->setArgument(1, $config['api']['uri'])
            ->setArgument(2, $config['api']['token']);

        if (!empty($config['sources'])) {
            $container->getDefinition('graph_client_bundle.cache')
                ->setArgument(2, $config['sources']['paths'])
                ->setArgument(3, $config['sources']['extension']);
        } else {
            $container->removeDefinition('graph_client_bundle.adapter');
            $container->removeDefinition('graph_client_bundle.cache');
            $container->removeDefinition('graph_client_bundle.warmer');
        }

        $container->getDefinition('graph_client_bundle.logger')
            ->setArgument(0, $config['logging_enabled'] ?? false);
    }

    /**
     * @param array $configs
     *
     * @return array
     */
    private function parseConfig(array $configs): array
    {
        $config = [];
        foreach ($configs as $configEnv) {
            $config = array_merge($config, $configEnv);
        }

        return $config;
    }
}
