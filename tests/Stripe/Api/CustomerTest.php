<?php


namespace Quartet\Stripe\Api;


use Quartet\Stripe\Api\Model\Customer;

class CustomerTest extends ApiTestCase
{
    public function testRetrieve()
    {
        $response = <<<JSON
{
  "id": "cus_9f8ojTFotEFqXB",
  "object": "customer",
  "account_balance": 0,
  "created": 1480575508,
  "currency": "jpy",
  "default_source": null,
  "delinquent": false,
  "description": null,
  "discount": null,
  "email": null,
  "livemode": false,
  "metadata": {
  },
  "shipping": null,
  "sources": {
    "object": "list",
    "data": [

    ],
    "has_more": false,
    "total_count": 0,
    "url": "/v1/customers/cus_9f8ojTFotEFqXB/sources"
  },
  "subscriptions": {
    "object": "list",
    "data": [

    ],
    "has_more": false,
    "total_count": 0,
    "url": "/v1/customers/cus_9f8ojTFotEFqXB/subscriptions"
  }
}
JSON;

        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.stripe.com/v1/customers/111', $this->withAuthorizationHeader(), [])
            ->will($this->returnResponse(200, $response));

        $customer = $this->stripe->customers()->retrieve(111);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('111', $customer->value()->id);
        $this->assertEquals(1480575508, $customer->value()->created);
    }

    public function testAll()
    {
        $listResponse = <<<JSON
{
  "object": "list",
  "url": "/v1/customers",
  "has_more": false,
  "data": [
    {
      "id": "cus_9f8ojTFotEFqXB",
      "object": "customer",
      "account_balance": 0,
      "created": 1480575508,
      "currency": "jpy",
      "default_source": null,
      "delinquent": false,
      "description": null,
      "discount": null,
      "email": null,
      "livemode": false,
      "metadata": {
      },
      "shipping": null,
      "sources": {
        "object": "list",
        "data": [
    
        ],
        "has_more": false,
        "total_count": 0,
        "url": "/v1/customers/cus_9f8ojTFotEFqXB/sources"
      },
      "subscriptions": {
        "object": "list",
        "data": [
    
        ],
        "has_more": false,
        "total_count": 0,
        "url": "/v1/customers/cus_9f8ojTFotEFqXB/subscriptions"
      }
    }
  ]
}
JSON;

        $this->http
            ->expects($this->exactly(1))
            ->method('request')
            ->with('get', $this->logicalOr(
                'https://api.stripe.com/v1/customers',
                'https://api.stripe.com/v1/customers/cus_9f8ojTFotEFqXB'
            ), $this->withAuthorizationHeader(), [
            ])
            ->will($this->onConsecutiveCalls(
                $this->returnResponse(200, $listResponse)
            ));

        /* @var Customer[] $customers */
        $customers = $this->stripe->customers()->all()->toArray();
        $this->assertCount(1, $customers);
        $this->assertInstanceOf(Customer::class, $customers[0]);
        $this->assertEquals('cus_9f8ojTFotEFqXB', $customers[0]->value()->id);
    }

    public function testCreate()
    {
        $response = <<<JSON
{
  "id": "cus_9f8odkG3vJNhSp",
  "object": "customer",
  "account_balance": 0,
  "created": 1480575507,
  "currency": "jpy",
  "default_source": null,
  "delinquent": false,
  "description": null,
  "discount": null,
  "email": null,
  "livemode": false,
  "metadata": {
  },
  "shipping": null,
  "sources": {
    "object": "list",
    "data": [

    ],
    "has_more": false,
    "total_count": 0,
    "url": "/v1/customers/cus_9f8odkG3vJNhSp/sources"
  },
  "subscriptions": {
    "object": "list",
    "data": [

    ],
    "has_more": false,
    "total_count": 0,
    "url": "/v1/customers/cus_9f8odkG3vJNhSp/subscriptions"
  }
}
JSON;

        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.stripe.com/v1/customers', $this->withAuthorizationHeader(), [])
            ->will($this->returnResponse(200, $response));

        $customer = $this->stripe->customers()->create();

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('cus_9f8odkG3vJNhSp', $customer->value()->id);
    }

    public function testUpdate()
    {
        $response = <<<JSON
{
  "id": "cus_9fA90MYIdeUzKQ",
  "object": "customer",
  "account_balance": 0,
  "created": 1480580536,
  "currency": "jpy",
  "default_source": null,
  "delinquent": false,
  "description": "Customer for daniel.martinez@example.com",
  "discount": null,
  "email": null,
  "livemode": false,
  "metadata": {
  },
  "shipping": null,
  "sources": {
    "object": "list",
    "data": [

    ],
    "has_more": false,
    "total_count": 0,
    "url": "/v1/customers/cus_9fA90MYIdeUzKQ/sources"
  },
  "subscriptions": {
    "object": "list",
    "data": [

    ],
    "has_more": false,
    "total_count": 0,
    "url": "/v1/customers/cus_9fA90MYIdeUzKQ/subscriptions"
  }
}
JSON;

        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.stripe.com/v1/customers/1111', $this->withAuthorizationHeader(), [])
            ->will($this->returnResponse(200, $response));

        $customer = $this->stripe->customers()->update('1111');

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('cus_9fA90MYIdeUzKQ', $customer->value()->id);
    }
}
