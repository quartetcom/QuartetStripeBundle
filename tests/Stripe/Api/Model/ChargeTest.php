<?php


namespace Quartet\Stripe\Api\Model;


use Quartet\Stripe\Api\ApiTestCase;
use Stripe;

class ChargeTest extends ApiTestCase
{
    /**
     * @var Charge
     */
    private $charge;

    protected function setUp()
    {
        parent::setUp();

        $this->mockHttpClient()
            ->expects($this->once())
            ->method('request')
            ->with('get', $this->stripeUrl('/charges/111'), $this->withAuthorizationHeader(), [])
            ->will($this->returnResponseFromFixture(200, '/charge/retrieve.json'));

        $this->charge = $this->stripe->charges()->retrieve(111);

        $this->mockHttpClient($this->http);
    }

    public function testSave()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', $this->stripeUrl('/charges/111'), $this->withAuthorizationHeader(), [
                'amount' => 999999,
            ])
            ->will($this->returnResponseFromFixture(200, '/charge/update.json'));

        $charge = $this->charge
            ->map(function (Stripe\Charge $delegate) {
                $delegate->amount = 999999;

                return $delegate;
            })
            ->save();

        $this->assertInstanceOf(Charge::class, $charge);
        $this->assertEquals(100, $charge->value()->amount);
    }

    public function testRefund()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', $this->stripeUrl('/charges/111/refund'), $this->withAuthorizationHeader(), [])
            ->will($this->returnResponse(200, '{"note":"there are no api doc on stripe.com"}'));

        $charge = $this->charge->refund();
        $this->assertInstanceOf(Charge::class, $charge);
    }

    public function testCapture()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', $this->stripeUrl('/charges/111/capture'), $this->withAuthorizationHeader(), [])
            ->will($this->returnResponseFromFixture(200, '/charge/capture.json'));

        $charge = $this->charge->capture([]);
        $this->assertEquals(true, $charge->value()->captured);
    }

    public function testMarkAsFraudulent()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', $this->stripeUrl('/charges/111'), $this->withAuthorizationHeader(), [
                'fraud_details' => [
                    'user_report' => 'fraudulent',
                ]
            ])
            ->will($this->returnResponseFromFixture(200, '/charge/update.json'));

        $charge = $this->charge->markAsFraudulent();
        $this->assertInstanceOf(Charge::class, $charge);
    }

    public function testMarkAsSafe()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', $this->stripeUrl('/charges/111'), $this->withAuthorizationHeader(), [
                'fraud_details' => [
                    'user_report' => 'safe',
                ]
            ])
            ->will($this->returnResponseFromFixture(200, '/charge/update.json'));

        $this->charge->markAsSafe();
    }
}
