<?php


namespace Quartet\Stripe\Api;


class ScopeTest extends ApiTestCase
{
    public function test()
    {
        $stripeA = $this->stripe('key A');
        $stripeB = $this->stripe('key B');

        $httpA = $this->mockHttpClient();
        $httpB = $this->mockHttpClient();

        $response = '{"id": "cus_9fAF5D0kSaanBh"}';

        $httpA
            ->expects($this->exactly(2))
            ->method('request')
            ->with('get', 'https://api.stripe.com/v1/customers/1', $this->withAuthorizationHeader('key A'), [])
            ->will($this->returnResponse(200, $response));

        $httpB
            ->expects($this->exactly(2))
            ->method('request')
            ->with('get', 'https://api.stripe.com/v1/customers/2', $this->withAuthorizationHeader('key B'), [])
            ->will($this->returnResponse(200, $response));

        $this->mockHttpClient($httpA);
        $stripeA->customers()->retrieve(1);

        $this->mockHttpClient($httpB);
        $stripeB->customers()->retrieve(2);


        $this->mockHttpClient($httpA);
        $stripeA->customers()->retrieve(1);

        $this->mockHttpClient($httpB);
        $stripeB->customers()->retrieve(2);
    }
}
