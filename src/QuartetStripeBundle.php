<?php

namespace Quartet\Bundle\StripeBundle;

use Quartet\Bundle\StripeBundle\DependencyInjection\QuartetStripeExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class QuartetStripeBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new QuartetStripeExtension();
    }
}
