<?php


namespace Quartet\Stripe\Http;


use Psr\Log\LoggerInterface;
use Stripe\HttpClient\ClientInterface;

class LoggingClient implements ClientInterface
{
    /**
     * @var ClientInterface
     */
    private $delegate;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * LoggingClient constructor.
     *
     * @param ClientInterface $delegate
     * @param LoggerInterface $logger
     */
    public function __construct(ClientInterface $delegate, LoggerInterface $logger)
    {
        $this->delegate = $delegate;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $absUrl, $headers, $params, $hasFile)
    {
        $this->logger->info('request', [
            'method' => $method,
            'url' => $absUrl,
            'headers' => $headers,
            'params' => $params,
        ]);

        list ($rawBody, $httpStatusCode, $httpHeader) = $this->delegate->request($method, $absUrl, $headers, $params, $hasFile);

        $this->logger->info('response', [
            'body' => $rawBody,
            'status' => $httpStatusCode,
            'header' => $httpHeader,
        ]);

        return [$rawBody, $httpStatusCode, $httpHeader];
    }
}
