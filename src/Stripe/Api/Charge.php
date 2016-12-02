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
    private $stripe;

    /**
     * Charge constructor.
     *
     * @param Stripe $stripe
     */
    public function __construct(Stripe $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * @param string $id The ID of the charge to retrieve.
     * @param array|string|null $options
     *
     * @return Model\Charge
     */
    public function retrieve($id, $options = null)
    {
        return $this->stripe->scope()->evaluate(function (Scope $scope) use ($id, $options) {
            $charge = StripeApi\Charge::retrieve($id, $options);

            return new Model\Charge($scope, $charge);
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
        return $this->stripe->scope()->evaluate(function (Scope $scope) use ($params, $options) {
            $collection = StripeApi\Charge::all($params, $options);

            return new Model\Collection($scope, $collection, function (StripeApi\Charge $charge) use ($scope) {
                return new Model\Charge($scope, $charge);
            });
        });
    }

    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Charge The created charge.
     */
    public function create($params = null, $options = null)
    {
        return $this->stripe->scope()->evaluate(function (Scope $scope) use ($params, $options) {
            $charge = StripeApi\Charge::create($params, $options);

            return new Model\Charge($scope, $charge);
        });
    }

    /**
     * @param string $id The ID of the charge to update.
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Charge The updated charge.
     */
    public function update($id, $params = null, $options = null)
    {
        return $this->stripe->scope()->evaluate(function (Scope $scope) use ($id, $params, $options) {
            $charge = StripeApi\Charge::update($id, $params, $options);

            return new Model\Charge($scope, $charge);
        });
    }
}
