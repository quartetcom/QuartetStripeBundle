<?php


namespace Quartet\Stripe\Scope\OverrideImpl;


use Stripe\ApiRequestor;
use Stripe\HttpClient\ClientInterface;

class HttpClientOverrideTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    private $defaultHttpClient;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    private $httpClient;

    /**
     * @var HttpClientOverride
     */
    private $override;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->defaultHttpClient = $this->createMock(ClientInterface::class);
        $this->httpClient = $this->createMock(ClientInterface::class);

        $this->override = new HttpClientOverride($this->httpClient);
    }

    public function test()
    {
        ApiRequestor::setHttpClient($this->defaultHttpClient);

        $propertyRef = new \ReflectionProperty(ApiRequestor::class, '_httpClient');
        $propertyRef->setAccessible(true);

        $this->assertSame($this->defaultHttpClient, $propertyRef->getValue());
        $this->assertNotSame($this->httpClient, $propertyRef->getValue());

        $this->override->execute(function () use ($propertyRef) {
            $this->assertNotSame($this->defaultHttpClient, $propertyRef->getValue());
            $this->assertSame($this->httpClient, $propertyRef->getValue());
        });

        $this->assertSame($this->defaultHttpClient, $propertyRef->getValue());
        $this->assertNotSame($this->httpClient, $propertyRef->getValue());
    }
}
