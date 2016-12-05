<?php


namespace Quartet\Stripe\Http;


use Stripe\HttpClient\ClientInterface;

class DebuggingClient implements ClientInterface
{
    /**
     * @var ClientInterface
     */
    private $delegate;

    /**
     * @var array
     */
    private $requests = [];

    /**
     * @var array
     */
    private $responses = [];

    /**
     * DebugClient constructor.
     *
     * @param ClientInterface $delegate
     */
    public function __construct(ClientInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $absUrl, $headers, $params, $hasFile)
    {
        $this->requests[] = [$method, $absUrl, $headers, $params, $hasFile];

        list ($rawBody, $httpStatusCode, $httpHeader) = $this->delegate->request($method, $absUrl, $headers, $params, $hasFile);

        $this->responses[] = [$rawBody, $httpStatusCode, $httpHeader];

        return [$rawBody, $httpStatusCode, $httpHeader];
    }

    /**
     * @return array
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @return array
     */
    public function getResponses()
    {
        return $this->responses;
    }
}
