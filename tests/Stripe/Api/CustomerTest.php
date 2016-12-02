<?php


namespace Quartet\Stripe\Api;


use Quartet\Stripe\Api\Model\Customer;

class CustomerTest extends ApiTestCase
{
    public function testRetrieve()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.stripe.com/v1/customers/111', $this->withAuthorizationHeader(), [])
            ->will($this->returnResponseFromFixture(200, '/customer/retrieve.json'));

        $customer = $this->stripe->customers()->retrieve(111);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('111', $customer->value()->id);
        $this->assertEquals(1480575508, $customer->value()->created);
    }

    public function testAll()
    {
        $this->http
            ->expects($this->exactly(1))
            ->method('request')
            ->with('get', 'https://api.stripe.com/v1/customers', $this->withAuthorizationHeader(), [])
            ->will($this->returnResponseFromFixture(200, '/customer/all.json'));

        /* @var Customer[] $customers */
        $customers = $this->stripe->customers()->all()->toArray();
        $this->assertCount(1, $customers);
        $this->assertInstanceOf(Customer::class, $customers[0]);
        $this->assertEquals('cus_9f8ojTFotEFqXB', $customers[0]->value()->id);
    }

    public function testCreate()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.stripe.com/v1/customers', $this->withAuthorizationHeader(), [])
            ->will($this->returnResponseFromFixture(200, '/customer/create.json'));

        $customer = $this->stripe->customers()->create();

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('cus_9f8odkG3vJNhSp', $customer->value()->id);
    }

    public function testUpdate()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.stripe.com/v1/customers/1111', $this->withAuthorizationHeader(), [])
            ->will($this->returnResponseFromFixture(200, '/customer/update.json'));

        $customer = $this->stripe->customers()->update('1111');

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('cus_9fA90MYIdeUzKQ', $customer->value()->id);
    }
}
