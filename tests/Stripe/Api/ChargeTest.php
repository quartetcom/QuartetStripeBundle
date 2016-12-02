<?php


namespace Quartet\Stripe\Api;


class ChargeTest extends ApiTestCase
{
    public function testRetrieve()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('get', $this->stripeUrl('/charges/1'), $this->withAuthorizationHeader(), [])
            ->will($this->returnResponseFromFixture(200, '/charge/retrieve.json'));

        $charge = $this->stripe->charges()->retrieve(1);
        $this->assertEquals(100, $charge->value()->amount);
    }

    public function testAll()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('get', $this->stripeUrl('/charges'), $this->withAuthorizationHeader(), [])
            ->will($this->returnResponseFromFixture(200, '/charge/all.json'));//

        /* @var Model\Charge[] $charges */
        $charges = $this->stripe->charges()->all()->toArray();
        $this->assertCount(1, $charges);
        $this->assertInstanceOf(Model\Charge::class, $charges[0]);
        $this->assertEquals(100, $charges[0]->value()->amount);
    }

    public function testCreate()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.stripe.com/v1/charges', $this->withAuthorizationHeader(), [
                'amount' => 2000,
                'currency' => 'jpy',
            ])
            ->will($this->returnResponseFromFixture(200, '/charge/create.json'));

        $charge = $this->stripe->charges()->create([
            'amount' => 2000,
            'currency' => 'jpy',
        ]);
        $this->assertInstanceOf(Model\Charge::class, $charge);
        $this->assertEquals(100, $charge->value()->amount);
    }

    public function testUpdate()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', $this->stripeUrl('/charges/666'), $this->withAuthorizationHeader(), [
                'amount' => 2000,
                'currency' => 'jpy',
            ])
            ->will($this->returnResponseFromFixture(200, '/charge/create.json'));

        $charge = $this->stripe->charges()->update(666, [
            'amount' => 2000,
            'currency' => 'jpy',
        ]);

        $this->assertInstanceOf(Model\Charge::class, $charge);
        $this->assertEquals(100, $charge->value()->amount);
    }
}
