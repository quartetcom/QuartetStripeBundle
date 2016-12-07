<?php


namespace Quartet\Stripe\Http;


use Stripe\HttpClient\ClientInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @var Response[]
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
        $this->requests[] = $request = $this->toSymfonyRequest($method, $absUrl, $headers, $params, $hasFile);

        $response = null;

        try {
            $response = $this->getResponse();
        } catch (\Exception $e) {
            throw new \LogicException(sprintf('No pseudo response for request: %s', $request));
        }

        return $this->toStripeResponse($response);
    }

    /**
     * @param string $body
     * @param int    $status
     * @param array  $headers
     */
    public function addResponse($body, $status = 200, array $headers = [])
    {
        $this->responses[] = new Response($body, $status, $headers);
    }

    /**
     * @return Request[]
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @return Request
     */
    public function getLastRequest()
    {
        if ($tail = array_slice($this->requests, -1)) {
            return $tail[0];
        }

        throw new \LogicException('There are no requests.');
    }

    /**
     * @return Response
     *
     * @throws \Exception
     */
    private function getResponse()
    {
        if ($response = array_shift($this->responses)) {
            return $response;
        }

        throw new \Exception('No more pseudo response');
    }

    /**
     * @param $method
     * @param $absUrl
     * @param $headers
     * @param $params
     * @param $hasFile
     *
     * @return Request
     */
    private function toSymfonyRequest($method, $absUrl, $headers, $params, $hasFile)
    {
        $headers = $this->toSymfonyHeaders($headers);

        $request = Request::create($absUrl, $method, $params, $cookies = [], $files = [], $server = []);
        $request->headers->add($headers);

        return $request;
    }

    /**
     * @param Response $response
     *
     * @return array
     */
    private function toStripeResponse(Response $response)
    {
        return [$response->getContent(), $response->getStatusCode(), $this->toStripeHeaders($response->headers)];
    }

    /**
     * @param array $headers
     *
     * @return array
     */
    private function toSymfonyHeaders(array $headers)
    {
        return array_reduce($headers, function (array $headers, $header) {
            if (preg_match('/^([^:]+):\s+?(.*)$/', $header, $matches)) {
                list($all, $key, $value) = $matches;

                return array_merge($headers, [$key => $value]);
            } else {
                return $headers;
            }
        }, []);
    }

    /**
     * @param HeaderBag $headers
     *
     * @return array
     */
    private function toStripeHeaders(HeaderBag $headers)
    {
        return array_reduce(array_keys($headers->keys()), function (array $acc, $key) use ($headers) {
            return array_merge($acc, [sprintf('%s: %s', $key, $headers->get($key))]);
        }, []);
    }
}
