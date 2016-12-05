<?php


namespace Quartet\StripeBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class QuartetStripeExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testApiSecret()
    {
        $apiSecret = 'foo bar baz';

        $container = $this->load($this->configs(['api_secret' => $apiSecret]));
        $this->assertEquals($apiSecret, $container->getDefinition('quartet.stripe')->getArgument(0));
    }

    /**
     * @dataProvider provideLoggerTests
     *
     * @param       $expected
     * @param array $configs
     */
    public function testLogger(\PHPUnit_Framework_Constraint $expected, array $configs)
    {
        $container = $this->load($configs);
        $client = $container->getDefinition('quartet.stripe')->getArgument(1);
        $this->assertThat($client, $expected);
    }

    /**
     * @return array
     */
    public function provideLoggerTests()
    {
        return [
            [$this->isNull(), $this->configs()],
            [$this->isReference('quartet.stripe.http'), $this->configs(['logger' => 'logger'])],
        ];
    }


    /**
     * @dataProvider provideDebugTests
     *
     * @param       $expected
     * @param array $configs
     */
    public function testDebug(\PHPUnit_Framework_Constraint $expected, array $configs)
    {
        $container = $this->load($configs);
        $client = $container->getDefinition('quartet.stripe')->getArgument(1);
        $this->assertThat($client, $expected);
    }

    /**
     * @test
     */
    public function provideDebugTests()
    {
        return [
            [$this->isNull(), $this->configs()],
            [$this->isNull(), $this->configs(['debug' => false])],
            [$this->isReference('quartet.stripe.http'), $this->configs(['debug' => true])],
        ];
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    private function configs(array $overrides = [])
    {
        return array_merge($this->defaultConfigs(), $overrides);
    }

    /**
     * @return array
     */
    private function defaultConfigs()
    {
        return [
            'api_public' => 'public-key',
            'api_secret' => 'secret-key',
        ];
    }

    /**
     * @param array $config
     *
     * @return ContainerBuilder
     */
    private function load(array $config)
    {
        $container = new ContainerBuilder();
        $extension = new QuartetStripeExtension();

        $extension->load(['quartet_stripe' => $config], $container);

        return $container;
    }

    /**
     * @param $id
     *
     * @return \PHPUnit_Framework_Constraint
     */
    private function isReference($id)
    {
        return $this->logicalAnd(
            $this->isInstanceOf(Reference::class),
            $this->callback(function (Reference $reference) use ($id) {
                return $id === $reference->__toString();
            })
        );
    }
}
