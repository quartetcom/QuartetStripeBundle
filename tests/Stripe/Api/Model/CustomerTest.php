<?php


namespace Quartet\Stripe\Api\Model;


use Stripe;

class CustomerTest extends ApiModelTestCase
{
    /**
     * @var Customer
     */
    private $customer;

    /**
     * @inheritDoc
     */
    protected function setupModel(\PHPUnit_Framework_MockObject_MockObject $httpClient)
    {
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.stripe.com/v1/customers/3', $this->withAuthorizationHeader())
            ->will($this->returnResponseFromFixture(200, '/customer/retrieve.json'));

        $this->customer = $this->stripe->customers()->retrieve(3);
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

    public function testSources()
    {
        /* @var Card[] $sources */
        $sources = $this->customer->sources()->toArray();
        $this->assertCount(1, $sources);
        $this->assertEquals('4242', $sources[0]->value()->last4);
    }

    public function testSourcesCreate()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', $this->stripeUrl('/customers/cus_9f8ojTFotEFqXB/sources'), $this->withAuthorizationHeader(), [
                'sources' => [
                    'foo' => 'bar',
                ],
            ])
            ->will($this->returnResponse(200, json_encode([
                'id' => 111,
                'object' => 'card',
            ])));

        /* @var Card $card */
        $card = $this->customer->sources()->create([
            'sources' => [
                'foo' => 'bar',
            ]
        ]);
        $this->assertEquals(111, $card->value()->id);
    }
}
