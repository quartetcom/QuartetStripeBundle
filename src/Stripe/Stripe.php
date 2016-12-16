<?php


namespace Quartet\Stripe;

use Quartet\Stripe\Api\Charge;
use Quartet\Stripe\Api\Customer;

class Stripe
{
    /**
     * @param string $apiKey
     *
     * @return StripeBuilder
     */
    static public function builder($apiKey)
    {
        return new StripeBuilder($apiKey);
    }

    /**
     * @param string $apiKey
     *
     * @return Stripe
     */
    public static function factory($apiKey)
    {
        return self::builder($apiKey)->get();
    }

    /**
     * @var Scope
     */
    private $scope;

    /**
     * Stripe constructor.
     *
     * @param Scope $scope
     */
    public function __construct(Scope $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return Charge
     */
    public function charges()
    {
        return new Charge($this->scope);
    }

    /**
     * @return Customer
     */
    public function customers()
    {
        return new Customer($this->scope);
    }
}
