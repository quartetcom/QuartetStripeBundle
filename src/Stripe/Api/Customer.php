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
        return $this->run(function () use ($id, $opts) {
            return Stripe\Customer::retrieve($id, $opts);
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
        $value = $this->scope->run(function () use ($params, $opts) {
            return Stripe\Customer::all($params, $opts);
        });

        return new Model\Collection($value, function (Stripe\Customer $customer) {
            return new Model\Customer($this->scope->value($customer));
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
        return $this->run(function () use ($params, $opts) {
            return Stripe\Customer::create($params, $opts);
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
        return $this->run(function () use ($id, $params, $options) {
            return Stripe\Customer::update($id, $params, $options);
        });
    }

    /**
     * @param callable $fn
     *
     * @return Model\Customer
     */
    private function run(Callable $fn)
    {
        return new Model\Customer($this->scope->run($fn));
    }
}
