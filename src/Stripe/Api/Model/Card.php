<?php


namespace Quartet\Stripe\Api\Model;

use Quartet\Stripe\Scope\Value;
use Stripe\Card as Delegate;

class Card
{
    /**
     * @var Value
     */
    private $value;

    /**
     * Card constructor.
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
     * @return Card The saved card
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
     * @return Card The deleted card
     */
    public function delete($params = null, $opts = null)
    {
        return $this->map(function (Delegate $delegate) use ($params, $opts) {
            return $delegate->delete($params, $opts);
        });
    }

    /**
     * @param callable $fn
     *
     * @return Card
     */
    public function map(Callable $fn)
    {
        $value = $this->value->map(function (Delegate $delegate) use ($fn) {
            return $fn($delegate);
        });

        return new self($value);
    }
}
