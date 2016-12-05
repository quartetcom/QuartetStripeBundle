<?php


namespace Quartet\Stripe\Scope\Override;



use Quartet\Stripe\Scope\Override;

class ChainOverride extends Override
{
    /**
     * @var Override[]
     */
    private $overrides;

    /**
     * ChainOverride constructor.
     *
     * @param Override[] $overrides
     */
    public function __construct(array $overrides)
    {
        $this->overrides = array_reduce($overrides, function (array $overrides, Override $override) {
            return array_merge($overrides, $override instanceof ChainOverride ? $override->overrides : [$override]);
        }, []);
    }

    /**
     * {@inheritdoc}
     */
    public function attach()
    {
        foreach ($this->overrides as $override) {
            $override->attach();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        foreach ($this->overrides as $override) {
            $override->detach();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function compose(Override $override)
    {
        return new self(array_merge($this->overrides, [$override]));
    }
}
