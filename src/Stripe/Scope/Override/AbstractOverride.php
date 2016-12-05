<?php


namespace Quartet\Stripe\Scope\Override;


use Quartet\Stripe\Scope\Override;

abstract class AbstractOverride extends Override
{
    /**
     * @var mixed
     */
    private $prev;

    /**
     * @return mixed
     */
    abstract protected function store();

    /**
     * @param mixed $stored
     *
     * @return void
     */
    abstract protected function restore($stored);

    /**
     * @return void
     */
    abstract protected function override();

    /**
     * {@inheritdoc}
     */
    public function attach()
    {
        $this->prev = $this->store();
        $this->override();
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        $this->restore($this->prev);
    }

    /**
     * {@inheritdoc}
     */
    public function compose(Override $override)
    {
        return new ChainOverride([$this, $override]);
    }
}
