<?php


namespace Quartet\Bundle\StripeBundle\Functional;


use Cartalyst\Stripe\Stripe;

class ServiceTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();

        static::bootKernel();
    }

    /**
     * @test
     */
    public function testStripeApiKey()
    {
        /* @var Stripe $stripe */
        $stripe = static::$kernel->getContainer()->get('quartet.stripe');
        $this->assertInstanceOf(Stripe::class, $stripe);
        $this->assertEquals('stripe api secret', $stripe->getApiKey());
    }
}
