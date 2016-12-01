<?php


namespace Quartet\Bundle\StripeBundle\Functional;


use Cartalyst\Stripe\Api;
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

    /**
     * @test
     */
    public function testStripeApiFactory()
    {
        $api = static::$kernel->getContainer()->get('quartet.stripe.api');

        $this->assertInstanceOf(Api\Account::class, $api->account());
        $this->assertInstanceOf(Api\ApplicationFeeRefunds::class, $api->applicationFeeRefunds());
        $this->assertInstanceOf(Api\ApplicationFees::class, $api->applicationFees());
        $this->assertInstanceOf(Api\Balance::class, $api->balance());
        $this->assertInstanceOf(Api\BankAccounts::class, $api->bankAccounts());
        $this->assertInstanceOf(Api\Bitcoin::class, $api->bitcoin());
        $this->assertInstanceOf(Api\Cards::class, $api->cards());
        $this->assertInstanceOf(Api\Charges::class, $api->charges());
        $this->assertInstanceOf(Api\CountrySpecs::class, $api->countrySpecs());
        $this->assertInstanceOf(Api\Coupons::class, $api->coupons());
        $this->assertInstanceOf(Api\Customers::class, $api->customers());
        $this->assertInstanceOf(Api\Disputes::class, $api->disputes());
        $this->assertInstanceOf(Api\Events::class, $api->events());
        $this->assertInstanceOf(Api\ExternalAccounts::class, $api->externalAccounts());
        $this->assertInstanceOf(Api\FileUploads::class, $api->fileUploads());
        $this->assertInstanceOf(Api\InvoiceItems::class, $api->invoiceItems());
        $this->assertInstanceOf(Api\Invoices::class, $api->invoices());
        $this->assertInstanceOf(Api\OrderReturns::class, $api->orderReturns());
        $this->assertInstanceOf(Api\Orders::class, $api->orders());
        $this->assertInstanceOf(Api\Plans::class, $api->plans());
        $this->assertInstanceOf(Api\Products::class, $api->products());
        $this->assertInstanceOf(Api\Recipients::class, $api->recipients());
        $this->assertInstanceOf(Api\Refunds::class, $api->refunds());
        $this->assertInstanceOf(Api\Skus::class, $api->skus());
        $this->assertInstanceOf(Api\Sources::class, $api->sources());
        $this->assertInstanceOf(Api\Subscriptions::class, $api->subscriptions());
        $this->assertInstanceOf(Api\Tokens::class, $api->tokens());
        $this->assertInstanceOf(Api\TransferReversals::class, $api->transferReversals());
        $this->assertInstanceOf(Api\Transfers::class, $api->transfers());
    }
}
