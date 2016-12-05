<?php


namespace Quartet\Stripe\Api\Model;

use Quartet\Stripe\Scope;
use Stripe\Customer as Delegate;
use Stripe as StripeApi;

class Customer
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var Delegate;
     */
    private $delegate;

    /**
     * Customer constructor.
     *
     * @param Scope    $scope
     * @param Delegate $delegate
     */
    public function __construct(Scope $scope, Delegate $delegate)
    {
        $this->scope = $scope;
        $this->delegate = $delegate;
    }

    /**
     * @return Delegate
     */
    public function value()
    {
        return $this->delegate;
    }

    /**
     * @param array|string|null $opts
     *
     * @return Customer The saved customer.
     */
    public function save($opts = null)
    {
        return $this->map(function (Delegate $delegate) use ($opts) {
            return $delegate->save($opts);
        });
    }

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return Customer The deleted customer.
     */
    public function delete($params = null, $opts = null)
    {
        return $this->map(function (Delegate $delegate) use ($params, $opts) {
            return $delegate->delete($params, $opts);
        });
    }

    /**
     * @param array|null $params
     *
     * @return Collection An array of the customer's Charges.
     */
    public function charges($params = null)
    {
        return $this->scope->evaluate(function (Scope $scope) use ($params) {
            /* @var StripeApi\Collection $collection */
            $collection = $this->delegate->charges($params);

            return new Collection($scope, $collection, function (StripeApi\Charge $charge) use ($scope) {
                return new Charge($scope, $charge);
            });
        });
    }

    /**
     * @return Collection An array of the customer's Card.
     */
    public function sources()
    {
        return new Collection($this->scope, $this->delegate->sources, function (StripeApi\Card $source) {
            return new Card($this->scope, $source);
        });
    }

    /**
     * @param callable $fn
     *
     * @return Customer
     */
    public function map(Callable $fn)
    {
        return $this->scope->evaluate(function (Scope $scope) use ($fn) {
            return new self($scope, $fn($this->delegate));
        });
    }
}
