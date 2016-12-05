<?php


namespace Quartet\Stripe;


use Psr\Log\LoggerInterface;
use Quartet\Stripe\Http\DebuggingClient;
use Quartet\Stripe\Http\LoggingClient;
use Quartet\Stripe\Scope\Override;
use Stripe\HttpClient;

class StripeBuilder
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var HttpClient\ClientInterface|null
     */
    private $httpClient;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * StripeBuilder constructor.
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param HttpClient\ClientInterface $httpClient
     *
     * @return $this
     */
    public function httpClient(HttpClient\ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    public function logger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param bool $debug
     *
     * @return $this
     */
    public function debug($debug = true)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @return Stripe
     */
    public function get()
    {
        $scope = new Scope(Override::apiKey($this->apiKey));

        if ($httpClient = $this->buildHttpClient()) {
            $scope = $scope->override(Override::httpClient($httpClient));
        }

        return new Stripe($scope);
    }

    /**
     * @return HttpClient\ClientInterface|null
     */
    private function buildHttpClient()
    {
        $http = $this->httpClient;

        if ($this->logger) {
            $http = new LoggingClient($http ?: HttpClient\CurlClient::instance(), $this->logger);
        }

        if ($this->debug) {
            $http = new DebuggingClient($http ?: HttpClient\CurlClient::instance(), $this->logger);
        }

        return $http;
    }
}
