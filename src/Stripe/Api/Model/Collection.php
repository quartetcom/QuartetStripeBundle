<?php


namespace Quartet\Stripe\Api\Model;

use Quartet\Stripe\Scope\Value;
use Stripe\Collection as Delegate;

class Collection implements \IteratorAggregate
{
    /**
     * @var Value
     */
    private $value;

    /**
     * @var callable
     */
    private $map;

    /**
     * Collection constructor.
     *
     * @param Value    $value
     * @param callable $map
     */
    public function __construct(Value $value, Callable $map)
    {
        $this->value = $value;
        $this->map = $map;
    }

    /**
     * @param null $params
     * @param null $opts
     *
     * @return object
     */
    public function create($params = null, $opts = null)
    {
        return $this->value
            ->map(function (Delegate $delegate) use ($params, $opts) {
                $value = $delegate->create($params, $opts);

                if (is_array($value)) {
                    throw new \UnexpectedValueException(sprintf('%s does not support Array return type currently.', __METHOD__));
                }

                return call_user_func($this->map, $value);
            })
            ->get();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->value
            ->map(function (Delegate $delegate) {
                $iterator = $delegate->autoPagingIterator();
                $elements = iterator_to_array($iterator);

                return array_map($this->map, $elements);
            })
            ->get();
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }
}
