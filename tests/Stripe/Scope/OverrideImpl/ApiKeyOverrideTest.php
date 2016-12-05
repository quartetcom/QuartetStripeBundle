<?php


namespace Quartet\Stripe\Scope\OverrideImpl;


use Quartet\Stripe\Scope\Override;
use Stripe\Stripe;

class ApiKeyOverrideTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Override
     */
    private $override;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->override = new ApiKeyOverride('foo bar');
    }

    public function test()
    {
        $apiKey = 'default api value';
        Stripe::setApiKey($apiKey);

        $this->assertEquals($apiKey, Stripe::getApiKey());

        $value = $this->override->execute(function () {
            $this->assertEquals('foo bar', Stripe::getApiKey());

            return 'return value';
        });

        $this->assertEquals('return value', $value);

        $this->assertEquals($apiKey, Stripe::getApiKey());
    }
}
