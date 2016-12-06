<?php


namespace Quartet\Stripe\Api\Model;


use Quartet\Stripe\Api\ApiTestCase;

abstract class ApiModelTestCase extends ApiTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $httpClient = $this->mockHttpClient();

        $this->setupModel($httpClient);

        $this->mockHttpClient($this->http);
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $httpClient
     */
    protected function setupModel(\PHPUnit_Framework_MockObject_MockObject $httpClient)
    {
    }
}
