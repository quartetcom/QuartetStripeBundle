<?php


namespace Quartet\Stripe\Api\Model;


use Quartet\Stripe\Scope;
use Stripe\Charge as Delegate;

class Charge
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var Delegate
     */
    private $delegate;

    /**
     * Charge constructor.
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
     * @param array|string|null $options
     *
     * @return Charge The saved charge.
     */
    public function save($options = null)
    {
        return $this->map(function () use ($options) {
            return $this->delegate->save($options);
        });
    }

    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Charge The refunded charge.
     */
    public function refund($params = null, $options = null)
    {
        return $this->map(function () use ($params, $options) {
            return $this->delegate->refund($params, $options);
        });
    }

    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Charge The captured charge.
     */
    public function capture($params = null, $options = null)
    {
        return $this->map(function () use ($params, $options) {
            return $this->delegate->capture($params, $options);
        });
    }

    /**
     * @param array|string|null $opts
     *
     * @return Charge The updated charge.
     */
    public function markAsFraudulent($opts = null)
    {
        return $this->map(function () use ($opts) {
            return $this->delegate->markAsFraudulent($opts);
        });
    }

    /**
     * @param array|string|null $opts
     *
     * @return Charge The updated charge.
     */
    public function markAsSafe($opts = null)
    {
        return $this->map(function () use ($opts) {
            return $this->delegate->markAsSafe($opts);
        });
    }

    /**
     * @param callable $fn
     *
     * @return Charge
     */
    public function map(Callable $fn)
    {
        return $this->scope->evaluate(function (Scope $scope) use ($fn) {
            return new self($scope, $fn($this->delegate));
        });
    }
}
