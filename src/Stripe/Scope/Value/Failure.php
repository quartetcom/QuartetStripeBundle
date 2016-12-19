<?php


namespace Quartet\Stripe\Scope\Value;


use Quartet\Stripe\Scope;
use Quartet\Stripe\Scope\Value;

class Failure implements Value
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var \Exception
     */
    private $exception;

    /**
     * Failure constructor.
     *
     * @param Scope $scope
     * @param       $exception
     */
    public function __construct(Scope $scope, \Exception $exception)
    {
        $this->scope = $scope;
        $this->exception = $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        throw $this->exception;
    }

    /**
     * {@inheritdoc}
     */
    public function map(Callable $fn)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function recover(Callable $fn)
    {
        return $this->scope->run(function () use ($fn) {
            return $fn($this->exception);
        });
    }
}
