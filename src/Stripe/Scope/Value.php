<?php


namespace Quartet\Stripe\Scope;


interface Value
{
    /**
     * @param callable $fn
     *
     * @return Value
     */
    public function map(Callable $fn);

    /**
     * @param callable $fn
     *
     * @return Value
     */
    public function recover(Callable $fn);

    /**
     * @return mixed
     */
    public function get();
}
