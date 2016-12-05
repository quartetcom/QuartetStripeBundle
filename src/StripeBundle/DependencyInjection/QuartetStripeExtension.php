<?php

namespace Quartet\StripeBundle\DependencyInjection;

use Quartet\Stripe\Http\DebuggingClient;
use Quartet\Stripe\Http\LoggingClient;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class QuartetStripeExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $configs = $this->processConfiguration($configuration, $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('stripe.yml');

        $definition = $container->getDefinition('quartet.stripe')
            ->replaceArgument(0, $configs['api_secret']);

        if ($configs['debug']) {
            // Highest priority http client to delegate some other client.
            $this->decorateHttpClient($container, 'quartet.stripe.http.debugging', DebuggingClient::class, true, 1);
        }

        if ($logger = $configs['logger']) {
            $this->decorateHttpClient($container, 'quartet.stripe.http.logging', LoggingClient::class)
                ->addArgument(new Reference($logger));
        }

        if ($configs['logger'] || $configs['debug']) {
            $definition->replaceArgument(1, new Reference('quartet.stripe.http'));
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param                  $id
     * @param                  $class
     * @param bool             $public
     *
     * @param int              $priority
     *
     * @return Definition
     */
    private function decorateHttpClient(ContainerBuilder $container, $id, $class, $public = false, $priority = 0)
    {
        return $container->register($id, $class)
            ->setDecoratedService('quartet.stripe.http', null, $priority)
            ->addArgument(new Reference($id.'.inner'))
            ->setPublic($public);
    }
}
