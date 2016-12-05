<?php


namespace Quartet\Stripe;

use Quartet\Stripe\Api\Charge;
use Quartet\Stripe\Api\Customer;
use Quartet\Stripe\Scope\Override;

class Stripe
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * Stripe constructor.
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->scope = (new Scope())
            ->override(Override::apiKey($apiKey));
    }

    /**
     * @return Scope
     */
    public function scope()
    {
        return $this->scope;
    }

    /**
     * @return Charge
     */
    public function charges()
    {
        return new Charge($this);
    }

    /**
     * @return Customer
     */
    public function customers()
    {
        return new Customer($this->scope);
    }
}
