<?php


namespace Quartet\Stripe\Api\Model;


use Quartet\Stripe\Scope\Value;
use Stripe\Charge as Delegate;

class Charge
{
    /**
     * @var Value
     */
    private $value;

    /**
     * Charge constructor.
     *
     * @param Value $value
     */
    public function __construct(Value $value)
    {
        $this->value = $value;
    }

    /**
     * @return Delegate
     */
    public function value()
    {
        return $this->value->get();
    }

    /**
     * @param array|string|null $options
     *
     * @return Charge The saved charge.
     */
    public function save($options = null)
    {
        return $this->map(function (Delegate $delegate) use ($options) {
            return $delegate->save($options);
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
        return $this->map(function (Delegate $delegate) use ($params, $options) {
            return $delegate->refund($params, $options);
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
        return $this->map(function (Delegate $delegate) use ($params, $options) {
            return $delegate->capture($params, $options);
        });
    }

    /**
     * @param array|string|null $opts
     *
     * @return Charge The updated charge.
     */
    public function markAsFraudulent($opts = null)
    {
        return $this->map(function (Delegate $delegate) use ($opts) {
            return $delegate->markAsFraudulent($opts);
        });
    }

    /**
     * @param array|string|null $opts
     *
     * @return Charge The updated charge.
     */
    public function markAsSafe($opts = null)
    {
        return $this->map(function (Delegate $delegate) use ($opts) {
            return $delegate->markAsSafe($opts);
        });
    }

    /**
     * @param callable $fn
     *
     * @return Charge
     */
    public function map(Callable $fn)
    {
        $value = $this->value->map(function (Delegate $delegate) use ($fn) {
            return $fn($delegate);
        });

        return new self($value);
    }
}
