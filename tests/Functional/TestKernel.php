<?php


namespace Quartet\Bundle\StripeBundle\Functional;


use Quartet\Bundle\StripeBundle\QuartetStripeBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new QuartetStripeBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $version = self::MAJOR_VERSION.'.'.self::MINOR_VERSION;

        $path = __DIR__.'/config/config.yml';
        $versionSpecificPath = __DIR__.'/config/'.$version.'/config.yml';

        if (file_exists($versionSpecificPath)) {
            $path = $versionSpecificPath;
        }

        $loader->load($path);
    }
}
