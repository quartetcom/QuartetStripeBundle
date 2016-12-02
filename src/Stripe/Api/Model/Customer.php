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
     * @return array An array of the customer's Charges.
     */
    public function charges($params = null)
    {
        return $this->scope->evaluate(function (Scope $scope) use ($params) {
            $charges = $this->delegate->charges($params);

            return array_map(function (StripeApi\Charge $charge) use ($scope) {
                return new Charge($scope, $charge);
            }, $charges);
        });
    }

    /**
     * @return Customer The updated customer.
     */
    public function deleteDiscount()
    {
        return $this->map(function (Delegate $delegate) {
            return $delegate->deleteDiscount();
        });
    }

    /**
     * @param callable $fn
     *
     * @return Customer
     */
    private function map(Callable $fn)
    {
        return $this->scope->evaluate(function (Scope $scope) use ($fn) {
            return new self($scope, $fn($this->delegate));
        });
    }
}
