<?php


namespace Quartet\Stripe\Scope\OverrideImpl;


use Quartet\Stripe\Scope\Override\AbstractOverride;
use Stripe\Stripe;

class ApiKeyOverride extends AbstractOverride
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * ApiKeyOverride constructor.
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @inheritDoc
     */
    protected function store()
    {
        return Stripe::getApiKey();
    }

    /**
     * @inheritDoc
     */
    protected function restore($stored)
    {
        return Stripe::setApiKey($stored);
    }

    /**
     * @inheritDoc
     */
    protected function override()
    {
        Stripe::setApiKey($this->apiKey);
    }
}
