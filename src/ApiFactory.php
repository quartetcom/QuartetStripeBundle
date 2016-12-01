<?php


namespace Quartet\Bundle\StripeBundle;


use Cartalyst\Stripe\Api;
use Cartalyst\Stripe\Stripe;

class ApiFactory
{
    /**
     * @var Stripe
     */
    private $stripe;

    /**
     * ServiceFactory constructor.
     *
     * @param Stripe $stripe
     */
    public function __construct(Stripe $stripe)
    {
        $this->stripe = $stripe;
    }
    /**
     * @return Api\Account
     */
    public function account()
    {
        return new Api\Account($this->stripe->getConfig());
    }

    /**
     * @return Api\ApplicationFeeRefunds
     */
    public function applicationFeeRefunds()
    {
        return new Api\ApplicationFeeRefunds($this->stripe->getConfig());
    }

    /**
     * @return Api\ApplicationFees
     */
    public function applicationFees()
    {
        return new Api\ApplicationFees($this->stripe->getConfig());
    }

    /**
     * @return Api\Balance
     */
    public function balance()
    {
        return new Api\Balance($this->stripe->getConfig());
    }

    /**
     * @return Api\BankAccounts
     */
    public function bankAccounts()
    {
        return new Api\BankAccounts($this->stripe->getConfig());
    }

    /**
     * @return Api\Bitcoin
     */
    public function bitcoin()
    {
        return new Api\Bitcoin($this->stripe->getConfig());
    }

    /**
     * @return Api\Cards
     */
    public function cards()
    {
        return new Api\Cards($this->stripe->getConfig());
    }

    /**
     * @return Api\Charges
     */
    public function charges()
    {
        return new Api\Charges($this->stripe->getConfig());
    }

    /**
     * @return Api\CountrySpecs
     */
    public function countrySpecs()
    {
        return new Api\CountrySpecs($this->stripe->getConfig());
    }

    /**
     * @return Api\Coupons
     */
    public function coupons()
    {
        return new Api\Coupons($this->stripe->getConfig());
    }

    /**
     * @return Api\Customers
     */
    public function customers()
    {
        return new Api\Customers($this->stripe->getConfig());
    }

    /**
     * @return Api\Disputes
     */
    public function disputes()
    {
        return new Api\Disputes($this->stripe->getConfig());
    }

    /**
     * @return Api\Events
     */
    public function events()
    {
        return new Api\Events($this->stripe->getConfig());
    }

    /**
     * @return Api\ExternalAccounts
     */
    public function externalAccounts()
    {
        return new Api\ExternalAccounts($this->stripe->getConfig());
    }

    /**
     * @return Api\FileUploads
     */
    public function fileUploads()
    {
        return new Api\FileUploads($this->stripe->getConfig());
    }

    /**
     * @return Api\InvoiceItems
     */
    public function invoiceItems()
    {
        return new Api\InvoiceItems($this->stripe->getConfig());
    }

    /**
     * @return Api\Invoices
     */
    public function invoices()
    {
        return new Api\Invoices($this->stripe->getConfig());
    }

    /**
     * @return Api\OrderReturns
     */
    public function orderReturns()
    {
        return new Api\OrderReturns($this->stripe->getConfig());
    }

    /**
     * @return Api\Orders
     */
    public function orders()
    {
        return new Api\Orders($this->stripe->getConfig());
    }

    /**
     * @return Api\Plans
     */
    public function plans()
    {
        return new Api\Plans($this->stripe->getConfig());
    }

    /**
     * @return Api\Products
     */
    public function products()
    {
        return new Api\Products($this->stripe->getConfig());
    }

    /**
     * @return Api\Recipients
     */
    public function recipients()
    {
        return new Api\Recipients($this->stripe->getConfig());
    }

    /**
     * @return Api\Refunds
     */
    public function refunds()
    {
        return new Api\Refunds($this->stripe->getConfig());
    }

    /**
     * @return Api\Skus
     */
    public function skus()
    {
        return new Api\Skus($this->stripe->getConfig());
    }

    /**
     * @return Api\Sources
     */
    public function sources()
    {
        return new Api\Sources($this->stripe->getConfig());
    }

    /**
     * @return Api\Subscriptions
     */
    public function subscriptions()
    {
        return new Api\Subscriptions($this->stripe->getConfig());
    }

    /**
     * @return Api\Tokens
     */
    public function tokens()
    {
        return new Api\Tokens($this->stripe->getConfig());
    }

    /**
     * @return Api\TransferReversals
     */
    public function transferReversals()
    {
        return new Api\TransferReversals($this->stripe->getConfig());
    }

    /**
     * @return Api\Transfers
     */
    public function transfers()
    {
        return new Api\Transfers($this->stripe->getConfig());
    }
}
