<?php


namespace Quartet\Stripe\Api;


use Quartet\Stripe\Scope;
use Stripe;

class Customer
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * Customer constructor.
     *
     * @param Scope $scope
     */
    public function __construct(Scope $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @param string $id The ID of the customer to retrieve.
     * @param array|string|null $opts
     *
     * @return Model\Customer
     */
    public function retrieve($id, $opts = null)
    {
        return $this->scope->evaluate(function (Scope $scope) use ($id, $opts) {
            $customer = Stripe\Customer::retrieve($id, $opts);

            return new Model\Customer($scope, $customer);
        });
    }

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return Model\Collection of Customers
     */
    public function all($params = null, $opts = null)
    {
        return $this->scope->evaluate(function (Scope $scope) use ($params, $opts) {
            $collection = Stripe\Customer::all($params, $opts);

            return new Model\Collection($scope, $collection, function (Stripe\Customer $customer) use ($scope) {
                return new Model\Customer($scope, $customer);
            });
        });
    }

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return Model\Customer The created customer.
     */
    public function create($params = null, $opts = null)
    {
        return $this->scope->evaluate(function (Scope $scope) use ($params, $opts) {
            $customer = Stripe\Customer::create($params, $opts);

            return new Model\Customer($scope, $customer);
        });
    }

    /**
     * @param string $id The ID of the customer to update.
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Model\Customer The updated customer.
     */
    public function update($id, $params = null, $options = null)
    {
        return $this->scope->evaluate(function (Scope $scope) use ($id, $params, $options) {
            $customer = Stripe\Customer::update($id, $params, $options);

            return new Model\Customer($scope, $customer);
        });
    }

}
