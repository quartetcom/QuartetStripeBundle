<?php


namespace Quartet\StripeBundle;


use Stripe\Error\Base as StripeException;
use Symfony\Component\Translation\TranslatorInterface;

class ErrorMessage
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ErrorMessage constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param StripeException $exception
     * @param string|null     $locale
     *
     * @return string
     */
    public function getLocalizedMessage(StripeException $exception, $locale = null)
    {
        return $this->translator->trans($exception->getMessage(), [], 'QuartetStripeBundle', $locale);
    }
}
