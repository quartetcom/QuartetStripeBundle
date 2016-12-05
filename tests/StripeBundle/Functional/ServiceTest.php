<?php


namespace Quartet\StripeBundle\Functional;

use Quartet\Stripe\Stripe;

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
    }
}
