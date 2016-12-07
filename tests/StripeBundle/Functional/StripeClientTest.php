<?php


namespace Quartet\StripeBundle\Functional;


use Quartet\Stripe\Http\DebuggingClient;
use Quartet\Stripe\Stripe;

class StripeClientTest extends WebTestCase
{
    /**
     * @var Stripe
     */
    private $stripe;

    /**
     * @var \SplFileInfo
     */
    private $log;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();
        static::bootKernel();

        $this->log = new \SplFileInfo(__DIR__.'/../Functional/logs/test.log');
        $this->unlinkIfExists($this->log);

        $this->stripe = static::$kernel->getContainer()->get('quartet.stripe');
    }

    /**
     * @test
     */
    public function testOverride()
    {
        /* @var DebuggingClient $client */
        $client = static::$kernel->getContainer()->get('quartet.stripe.http.debugging');
        $client->addResponse('{"id":"11111"}');

        $this->assertFileNotExists($this->log->getPathname());

        $this->stripe->customers()->retrieve(1);

        // logger
        $this->assertFileExists($this->log->getPathname());
        $this->assertContains('app.INFO: request {"method":"get","url":"https://api.stripe.com/v1/customers/1"', file_get_contents($this->log->getPathname()));

        // debugger
        $request = $client->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('https://api.stripe.com/v1/customers/1', $request->getUri());
        $this->assertEquals('Bearer stripe api secret', $request->headers->get('Authorization'));
    }

    private function unlinkIfExists(\SplFileInfo $file)
    {
        if (file_exists($file->getPathname())) {
            unlink($file->getPathname());
        }
    }
}
