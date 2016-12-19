<?php


namespace Quartet\Stripe;


use Quartet\Stripe\Scope\Override;
use Quartet\Stripe\Scope\Value;
use Stripe as StripeApi;

class Scope
{
    /**
     * @var Override
     */
    private $override;

    /**
     * Scope constructor.
     *
     * @param Override $override
     */
    public function __construct(Override $override = null)
    {
        $this->override = $override ?: new Override\NoopOverride();
    }

    /**
     * @param callable $fn
     *
     * @return Value
     */
    public function run(Callable $fn)
    {
        try {
            $value = $this->override->execute(function () use ($fn) {
                return $fn($this);
            });

            return new Value\Success($this, $value);
        } catch (\Exception $e) {
            return new Value\Failure($this, $e);
        }
    }

    /**
     * @param Override $override
     *
     * @return Scope
     */
    public function override(Override $override)
    {
        return new self($this->override->compose($override));
    }

    /**
     * @param callable $fn
     *
     * @return mixed
     */
    public function evaluate(Callable $fn)
    {
        return $this->run($fn)->get();
    }

    /**
     * @param mixed $value
     *
     * @return Value
     */
    public function value($value)
    {
        return new Value\Success($this, $value);
    }
}
