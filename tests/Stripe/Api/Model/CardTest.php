<?php


namespace Quartet\Stripe\Api\Model;


class CardTest extends ApiModelTestCase
{
    /**
     * @var Customer
     */
    private $customer;

    /**
     * @inheritdoc
     */
    protected function setupModel(\PHPUnit_Framework_MockObject_MockObject $httpClient)
    {
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('get', $this->stripeUrl('/customers/111'), $this->withAuthorizationHeader(), [])
            ->will($this->returnResponseFromFixture(200, '/customer/retrieve.json'));

        $this->customer = $this->stripe->customers()->retrieve(111);
    }

    /**
     * @test
     */
    public function testSave()
    {
        /* @var Card $card */
        $card = $this->customer->sources()->toArray()[0];

        $card->value()->name = 'new name';

        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', $this->stripeUrl('/customers/cus_9f8ojTFotEFqXB/sources/card_19MCGYH5bhTTjb4oeasYK0bH'), $this->withAuthorizationHeader(), [
                'name' => 'new name',
            ])
            ->will($this->returnResponseFromFixture(200, '/source/one.json'));

        $card->save();
    }

    /**
     * @test
     */
    public function testCreate()
    {
        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('post', $this->stripeUrl('/customers/cus_9f8ojTFotEFqXB/sources'), $this->withAuthorizationHeader(), [
                'source' => [
                    'object' => 'card',
                    'exp_month' => 11,
                    'exp_year' => 2222,
                    'number' => 33333333,
                    'name' => 'FOO BAR',
                ],
            ])
            ->will($this->returnResponseFromFixture(200, '/source/one.json'));

        /* @var Card $card */
        $card = $this->customer->sources()->create(['source' => [
            'object' => 'card',
            'exp_month' => 11,
            'exp_year' => 2222,
            'number' => 33333333,
            'name' => 'FOO BAR',
        ]]);

        $this->assertInstanceOf(Card::class, $card);

        $value = $card->value();
        $this->assertEquals('12', $value->exp_month);
        $this->assertEquals('2025', $value->exp_year);
    }

    public function testDelete()
    {
        /* @var Card $card */
        $card = $this->customer->sources()->toArray()[0];

        $this->http
            ->expects($this->once())
            ->method('request')
            ->with('delete', $this->stripeUrl('/customers/cus_9f8ojTFotEFqXB/sources/card_19MCGYH5bhTTjb4oeasYK0bH'), $this->withAuthorizationHeader(), [])
            ->will($this->returnResponseFromFixture(200, '/source/delete.json'));

        $this->assertFalse(isset($card->value()->deleted));

        $card = $card->delete();
        $this->assertTrue($card->value()->deleted);
    }
}
