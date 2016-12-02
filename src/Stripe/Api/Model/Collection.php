<?php


namespace Quartet\Stripe\Api\Model;

use Quartet\Stripe\Scope;
use Stripe\Collection as Delegate;
use Traversable;

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
