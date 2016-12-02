<?php


namespace Quartet\Stripe;


use Stripe as StripeApi;

class Scope
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * Scope constructor.
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param callable $fn
     *
     * @return Scope\Value
     */
    public function run(Callable $fn)
    {
        $apiKey = StripeApi\Stripe::getApiKey();

        StripeApi\Stripe::setApiKey($this->apiKey);

        $value = $fn($this);

        StripeApi\Stripe::setApiKey($apiKey);

        return new Scope\Value($this, $value);
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
}
