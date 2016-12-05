<?php


namespace Quartet\Stripe\Scope;


use Stripe\HttpClient\ClientInterface;

abstract class Override
{
    /**
     * @return Override
     */
    public static function noop()
    {
        return new Override\NoopOverride();
    }

    /**
     * @param string $apiKey
     *
     * @return Override
     */
    public static function apiKey($apiKey)
    {
        return new OverrideImpl\ApiKeyOverride($apiKey);
    }

    /**
     * @param ClientInterface $client
     *
     * @return Override
     */
    public static function httpClient(ClientInterface $client)
    {
        return new OverrideImpl\HttpClientOverride($client);
    }

    /**
     * @return void
     */
    abstract public function attach();

    /**
     * @return void
     */
    abstract public function detach();

    /**
     * @param Override $override
     *
     * @return Override
     */
    abstract public function compose(Override $override);

    /**
     * @param callable $fn
     *
     * @return mixed
     */
    public function execute(Callable $fn)
    {
        $this->attach();

        $value = $fn();

        $this->detach();

        return $value;
    }
}
