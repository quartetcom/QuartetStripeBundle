<?php


namespace Quartet\Stripe\Api\Model;


use Quartet\Stripe\Api\ApiTestCase;
use Stripe;

class CustomerTest extends ApiTestCase
{
    /**
     * @var Customer
     */
    private $customer;

    protected function setUp()
    {
        parent::setUp();

        $http = $this->mockHttpClient();
        $http
            ->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.stripe.com/v1/customers/3', $this->withAuthorizationHeader())
            ->will($this->returnResponseFromFixture(200, '/customer/retrieve.json'));

        $this->customer = $this->stripe->customers()->retrieve(3);

        $this->mockHttpClient($this->http);
    }

    public function testDelete()
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('delete')
            ->will($this->returnResponseFromFixture(200, '/customer/delete.json'));

        $deleted = $this->customer->delete();
        $this->assertInstanceOf(Customer::class, $deleted);
    }

    public function testSave()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.stripe.com/v1/customers/3', $this->withAuthorizationHeader(), [
                'email' => 'new email',
            ])
            ->will($this->returnResponseFromFixture(200, '/customer/update.json'));

        $customer = $this->customer
            ->map(function (Stripe\Customer $delegate) {
                $delegate->email = 'new email';

                return $delegate;
            })
            ->save();

        $this->assertEquals('Customer for daniel.martinez@example.com', $customer->value()->description);
    }

    public function testCharges()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.stripe.com/v1/charges', $this->withAuthorizationHeader(), [
                'customer' => 3,
            ])
            ->will($this->returnResponseFromFixture(200, '/charge/all.json'));//

        /* @var Charge[] $charges */
        $charges = $this->customer->charges()->toArray();
        $this->assertCount(1, $charges);
        $this->assertInstanceOf(Charge::class, $charges[0]);
        $this->assertEquals(100, $charges[0]->value()->amount);
    }
}