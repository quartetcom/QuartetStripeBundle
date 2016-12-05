<?php


namespace Quartet\Stripe\Api\Model;

use Quartet\Stripe\Scope;
use Stripe\Collection as Delegate;

class Collection implements \IteratorAggregate
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
     * @var callable
     */
    private $map;

    /**
     * Collection constructor.
     *
     * @param Scope    $scope
     * @param Delegate $delegate
     * @param callable $map
     */
    public function __construct(Scope $scope, Delegate $delegate, Callable $map)
    {
        $this->scope = $scope;
        $this->delegate = $delegate;
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
        return $this->scope->evaluate(function () use ($params, $opts) {
            $value = $this->delegate->create($params, $opts);

            if (is_array($value)) {
                throw new \UnexpectedValueException(sprintf('%s does not support Array return type currently.', __METHOD__));
            }

            return call_user_func($this->map, $value);
        });
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->scope->evaluate(function () {
            $iterator = $this->delegate->autoPagingIterator();
            $elements = iterator_to_array($iterator);

            return array_map($this->map, $elements);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }
}
