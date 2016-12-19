<?php


namespace Quartet\Stripe\Scope\OverrideImpl;


use Quartet\Stripe\Scope\Override\AbstractOverride;
use Stripe\ApiRequestor;
use Stripe\HttpClient\ClientInterface;

class HttpClientOverride extends AbstractOverride
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var \ReflectionMethod
     */
    private $reflection;

    /**
     * HttpClientOverride constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->reflection = new \ReflectionProperty(ApiRequestor::class, '_httpClient');
        $this->reflection->setAccessible(true);
    }

    /**
     * {@inheritdoc}
     */
    protected function store()
    {
        return $this->reflection->getValue();
    }

    /**
     * {@inheritdoc}
     */
    protected function restore($stored)
    {
        $this->reflection->setValue($stored);
    }

    /**
     * {@inheritdoc}
     */
    protected function override()
    {
        ApiRequestor::setHttpClient($this->client);
    }
}
