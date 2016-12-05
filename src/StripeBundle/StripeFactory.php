<?php


namespace Quartet\StripeBundle;


use Quartet\Stripe\Stripe;
use Stripe\HttpClient\ClientInterface;

class StripeFactory
{
    /**
     * @param string          $apiKey
     * @param ClientInterface $client
     *
     * @return Stripe
     */
    public function create($apiKey, ClientInterface $client = null)
    {
        $builder = Stripe::builder($apiKey)
            ->httpClient($client);

        return $builder->get();
    }
}
