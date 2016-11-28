<?php

namespace Quartet\Bundle\StripeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('quartet_stripe');

        $this->addGlobalConfig($root);

        return $builder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addGlobalConfig(ArrayNodeDefinition $node)
    {
        $node
            ->beforeNormalization()
                ->ifTrue(function (array $config) {
                    return !array_key_exists('test', $config);
                })
                ->then(function (array $config) {
                    $api = $config['api_secret'];

                    if (preg_match('/^test_/', $api)) {
                        $config['test'] = true;
                    } elseif (preg_match('/live_/', $api)) {
                        $config['test'] = false;
                    }

                    return $config;
                })
            ->end()
            ->children()
                ->scalarNode('api_secret')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('api_public')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->booleanNode('test')
                    ->isRequired()
                    ->info('Will be inferred from `api_secret` when you do not configure')
                ->end()
            ->end()
        ;
    }
}
