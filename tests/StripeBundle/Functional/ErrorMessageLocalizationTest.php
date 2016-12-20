<?php


namespace Quartet\StripeBundle\Functional;


use Quartet\StripeBundle\ErrorMessage;
use Stripe\Error as StripeException;

class ErrorMessageLocalizationTest extends WebTestCase
{
    /**
     * @var ErrorMessage
     */
    private $errorMessage;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        static::bootKernel();

        $this->errorMessage = static::$kernel->getContainer()->get('quartet.stripe.error_message');
    }

    public function test()
    {
        $error = new StripeException\Api('The card number is not a valid credit card number.');

        $this->assertEquals('カード番号が不正です。', $this->errorMessage->getLocalizedMessage($error, 'ja'));
    }
}
