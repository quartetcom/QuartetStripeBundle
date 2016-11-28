<?php

namespace Quartet\Bundle\StripeBundle\DependencyInjection;

use Stripe\Stripe;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class QuartetStripeExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var QuartetStripeExtension
     */
    private $loader;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->loader = new QuartetStripeExtension();
        Stripe::setApiKey(null);
    }

    /**
     * @test
     * @dataProvider provideRequiredConfigurationNames
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function throwExceptionUnlessConfigureRequired($requiredKey)
    {
        $config = $this->getEmptyConfig();
        unset($config[$requiredKey]);
        $this->loader->load([$config], $this->container);
    }

    /**
     * @return array
     */
    public function provideRequiredConfigurationNames()
    {
        return [
            ['api_public'],
            ['api_secret'],
            ['test'],
        ];
    }

    /**
     * @test
     */
    public function defaultConfiguration()
    {
        $config = $this->getEmptyConfig();
        $this->loader->load([$config], $this->container);

        $this->assertParameter('my_api_secret_key', 'quartet_stripe.api_secret');
        $this->assertParameter('my_api_public_key', 'quartet_stripe.api_public');
        $this->assertParameter(false, 'quartet_stripe.test');
    }

    /**
     * @test
     */
    public function testConfig()
    {
        $config = $this->getEmptyConfig();
        $config['test'] = true;
        $this->loader->load([$config], $this->container);

        $this->assertParameter(true, 'quartet_stripe.test');
    }

    /**
     * @test
     * @dataProvider provideInferTestModeTests
     * @param $expectedMode
     * @param $apiPublic
     */
    public function testInferTestMode($expectedMode, $apiPublic)
    {
        $config = $this->getEmptyConfig();
        $config['api_secret'] = $apiPublic;
        unset($config['test']);
        $this->loader->load([$config], $this->container);

        $this->assertParameter($expectedMode, 'quartet_stripe.test');
    }

    /**
     * @return array
     */
    public function provideInferTestModeTests()
    {
        return [
            [false, 'live_foobar'],
            [true, 'test_foobar'],
        ];
    }

    /**
     * @test
     */
    public function setApiKey()
    {
        $this->assertEmpty(Stripe::getApiKey());

        $config = $this->getEmptyConfig();
        $this->loader->load([$config], $this->container);

        $this->assertSame($this->container->getParameter('quartet_stripe.api_secret'), Stripe::getApiKey());
    }

    /**
     * @param $value
     * @param $key
     */
    private function assertParameter($value, $key)
    {
        $this->assertSame($value, $this->container->getParameter($key));
    }

    /**
     * @return array
     */
    private function getEmptyConfig()
    {
        return [
            'api_secret' => 'my_api_secret_key',
            'api_public' => 'my_api_public_key',
            'test' => false,
        ];
    }
}
