<?php


namespace Quartet\Stripe\Scope\Override;


use Quartet\Stripe\Scope\Override;

class NoopOverride extends Override
{
    /**
     * {@inheritdoc}
     */
    public function attach()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function compose(Override $override)
    {
        return $override;
    }
}
