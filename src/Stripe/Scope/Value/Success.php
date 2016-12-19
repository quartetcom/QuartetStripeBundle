<?php


namespace Quartet\Stripe\Scope\Value;


use Quartet\Stripe\Scope;
use Quartet\Stripe\Scope\Value;

class Success implements Value
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Value constructor.
     *
     * @param Scope $scope
     * @param mixed $value
     */
    public function __construct(Scope $scope, $value)
    {
        $this->scope = $scope;
        $this->value = $value;
    }

    /**
     * @param callable $fn
     *
     * @return Value
     */
    public function map(Callable $fn)
    {
        return $this->scope->run(function () use ($fn) {
            return $fn($this->value);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function recover(Callable $fn)
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }
}
