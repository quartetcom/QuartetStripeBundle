<?php


namespace Quartet\Stripe\Api;


use Quartet\Stripe\Scope;
use Quartet\Stripe\Stripe;
use Stripe as StripeApi;

class Charge
{
    /**
     * @var Stripe
     */
    private $scope;

    /**
     * Charge constructor.
     *
     * @param Scope $scope
     */
    public function __construct(Scope $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @param string $id The ID of the charge to retrieve.
     * @param array|string|null $options
     *
     * @return Model\Charge
     */
    public function retrieve($id, $options = null)
    {
        return $this->run(function () use ($id, $options) {
            return StripeApi\Charge::retrieve($id, $options);
        });
    }

    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Model\Collection of Charges
     */
    public function all($params = null, $options = null)
    {
        $collection = $this->scope->run(function () use ($params, $options) {
            return StripeApi\Charge::all($params, $options);
        });

        return new Model\Collection($collection, function (StripeApi\Charge $charge) {
            return new Model\Charge($this->scope->value($charge));
        });
    }

    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Model\Charge The created charge.
     */
    public function create($params = null, $options = null)
    {
        return $this->run(function () use ($params, $options) {
            return StripeApi\Charge::create($params, $options);
        });
    }

    /**
     * @param string $id The ID of the charge to update.
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Model\Charge The updated charge.
     */
    public function update($id, $params = null, $options = null)
    {
        return $this->run(function () use ($id, $params, $options) {
            return StripeApi\Charge::update($id, $params, $options);
        });
    }

    /**
     * @param callable $fn
     *
     * @return Model\Charge
     */
    private function run(Callable $fn)
    {
        return new Model\Charge($this->scope->run($fn));
    }
}
