<?php


namespace Quartet\Stripe\Http;


use Stripe\HttpClient\ClientInterface;
use Symfony\Component\HttpFoundation\Request;

class DebuggingClient implements ClientInterface
{
    /**
     * @var ClientInterface
     */
    private $delegate;

    /**
     * @var Request[]
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
        $headers = $this->parseStripeHeaders($headers);

        $request = Request::create($absUrl, $method, $params, $cookies = [], $files = [], $server = []);
        $request->headers->add($headers);

        $this->requests[] = $request;

        return $this->getResponse();
    }

    /**
     * @param       $status
     * @param       $body
     * @param array $headers
     */
    public function addResponse($status, $body, array $headers = [])
    {
        $this->responses[] = [$body, $status, $headers];
    }

    /**
     * @return array
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if ($request = array_shift($this->requests)) {
            return $request;
        }

        throw new \LogicException('There are no expected request.');
    }

    /**
     * @return array
     */
    private function getResponse()
    {
        if ($response = array_shift($this->responses)) {
            return $response;
        }

        throw new \LogicException('No more pseudo response');
    }

    /**
     * @param array $headers
     *
     * @return array|mixed
     */
    private function parseStripeHeaders(array $headers)
    {
        $headers = array_reduce($headers, function (array $headers, $header) {
            if (preg_match('/^([^:]+):\s+?(.*)$/', $header, $matches)) {
                list($all, $key, $value) = $matches;

                return array_merge($headers, [$key => $value]);
            } else {
                return $headers;
            }
        }, []);

        return $headers;
    }
}
