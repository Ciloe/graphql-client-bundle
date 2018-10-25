<?php

namespace GraphQLClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * @param string $kernelRootDir
     */
    public function __construct(string $kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }

    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder
            ->root('graphql_client')
                ->children()
                    ->arrayNode('sources')
                        ->children()
                            ->arrayNode('paths')
                                ->children()
                                    ->arrayNode('queries')
                                        ->prototype('scalar')->end()
                                        ->isRequired()
                                        ->requiresAtLeastOneElement()
                                        ->defaultValue(["{$this->kernelRootDir}/Resources/graphql/queries"])
                                        ->info("The array contains the graphql file path sources for queries")
                                    ->end()
                                    ->arrayNode('fragments')
                                        ->prototype('scalar')->end()
                                        ->isRequired()
                                        ->requiresAtLeastOneElement()
                                        ->defaultValue(["{$this->kernelRootDir}/Resources/graphql/fragments"])
                                        ->info("The array contains the graphql file path sources for fragments")
                                    ->end()
                                ->end()
                            ->end()
                            ->scalarNode('extension')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->defaultValue('.graphql')
                                ->info("The generic graphql file extension")
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('api')
                        ->isRequired()
                        ->cannotBeEmpty()
                        ->children()
                            ->scalarNode('host')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->info("The api host")
                            ->end()
                            ->scalarNode('uri')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->info("The api uri")
                            ->end()
                            ->scalarNode('token')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->info("The api token key")
                            ->end()
                        ->end()
                    ->end()
                    ->booleanNode('logger_enabled')
                        ->defaultFalse()
                        ->info("Set if used logger")
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
