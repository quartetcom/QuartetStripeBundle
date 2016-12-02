<?php


namespace Quartet\Stripe\Api;


use Quartet\Stripe\Stripe;
use Stripe\ApiRequestor;
use Stripe\ApiResource;
use Stripe\HttpClient\ClientInterface;

class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Stripe
     */
    protected $stripe;
    protected $apiKey;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    protected $http;

    protected function setUp()
    {
        parent::setUp();

        $this->stripe = $this->stripe($this->apiKey = 'test api key');
        $this->http = $this->mockHttpClient();
    }

    /**
     * @param $apiKey
     *
     * @return Stripe
     */
    public function stripe($apiKey)
    {
        return new Stripe($apiKey);
    }

    /**
     * @param string $apiKey
     *
     * @return \PHPUnit_Framework_Constraint
     */
    public function withAuthorizationHeader($apiKey = null)
    {
        $apiKey = $apiKey ?: $this->apiKey;

        return $this->contains("Authorization: Bearer {$apiKey}");
    }

    /**
     * @param       $code
     * @param       $body
     * @param array $header
     *
     * @return \PHPUnit_Framework_MockObject_Stub
     */
    public function returnResponse($code, $body, array $header = [])
    {
        return $this->returnValue([$body, $code, $header]);
    }

    /**
     * @param ClientInterface $http
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    public function mockHttpClient(ClientInterface $http = null)
    {
        $http = $http ?: $this->createMock(ClientInterface::class);

        ApiRequestor::setHttpClient($http);

        return $http;
    }
}
