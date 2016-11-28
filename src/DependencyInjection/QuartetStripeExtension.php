<?php

namespace Quartet\Bundle\StripeBundle\DependencyInjection;

use Stripe\Stripe;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class QuartetStripeExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $configs = $this->processConfiguration($configuration, $config);

        $this->remapParameters($container, $configs, 'quartet_stripe.%s', ['api_secret', 'api_public', 'test']);

        Stripe::setApiKey($container->getParameter('quartet_stripe.api_secret'));
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'quartet_stripe';
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $configs
     * @param string           $path
     * @param array            $keys
     */
    private function remapParameters(ContainerBuilder $container, array $configs, $path, array $keys)
    {
        foreach ($keys as $key) {
            $container->setParameter(sprintf($path, $key), $configs[$key]);
        }
    }
}
