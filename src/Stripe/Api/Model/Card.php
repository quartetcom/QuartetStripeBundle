<?php


namespace Quartet\Stripe\Api\Model;

use Quartet\Stripe\Scope;
use Stripe\Card as Delegate;

class Card
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
     * Card constructor.
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
     * @param callable $fn
     *
     * @return Card
     */
    public function map(Callable $fn)
    {
        return $this->scope->evaluate(function (Scope $scope) use ($fn) {
            return new self($scope, $fn($this->delegate));
        });
    }
}
