<?php


namespace Quartet\Stripe\Api\Model;

use Quartet\Stripe\Scope\Value;
use Stripe as StripeApi;
use Stripe\Customer as Delegate;

class Customer
{
    /**
     * @var Value
     */
    private $value;

    /**
     * Customer constructor.
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
        $value = $this->value->map(function (Delegate $delegate) use ($params) {
            /* @var StripeApi\Collection $collection */
            return $delegate->charges($params);
        });

        return new Collection($value, function (StripeApi\Charge $charge) {
            $value = $this->value->map(function () use ($charge) {
                return $charge;
            });

            return new Charge($value);
        });
    }

    /**
     * @return Collection An array of the customer's Card.
     */
    public function sources()
    {
        $sources = $this->value->map(function () {
            return $this->value()->sources;
        });

        return new Collection($sources, function (StripeApi\Card $source) {
            $value = $this->value->map(function () use ($source) {
                return $source;
            });

            return new Card($value);
        });
    }

    /**
     * @param callable $fn
     *
     * @return Customer
     */
    public function map(Callable $fn)
    {
        $value = $this->value->map(function (Delegate $delegate) use ($fn) {
            return $fn($delegate);
        });

        return new self($value);
    }
}
