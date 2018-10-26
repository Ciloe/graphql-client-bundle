<?php

namespace AlloCine\GraphClient\Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class GraphClientExtension extends Extension
{
    /**
     * @var array
     */
    private $config;

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
        $loader->load('services.yml');

        $container->getDefinition('graph_client_bundle.api_model')
            ->setArgument(0, $this->config['api']['host'])
            ->setArgument(1, $this->config['api']['uri'])
            ->setArgument(2, $this->config['api']['token']);

        if (!empty($this->config['sources'])) {
            $container->getDefinition('graph_client_bundle.cache')
                ->setArgument(2, $this->config['sources']['paths'])
                ->setArgument(3, $this->config['sources']['extension']);
        } else {
            $container->removeDefinition('graph_client_bundle.adapter');
            $container->removeDefinition('graph_client_bundle.cache');
            $container->removeDefinition('graph_client_bundle.warmer');
        }

        $container->getDefinition('graph_client_bundle.logger')
            ->setArgument(0, $this->config['logging_enabled'] ?? false);
    }
}
