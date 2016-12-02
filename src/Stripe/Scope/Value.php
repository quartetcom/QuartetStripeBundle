<?php


namespace Quartet\Stripe\Scope;


use Quartet\Stripe\Scope;

class Value
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
        return $this->scope->run(function (Scope $scope) use ($fn) {
            return $fn($this->value, $scope);
        });
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }
}
