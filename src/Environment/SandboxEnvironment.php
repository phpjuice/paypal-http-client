<?php

namespace PayPal\Http\Environment;

class SandboxEnvironment extends PayPalEnvironment
{
    public function __construct($clientId, $clientSecret)
    {
        parent::__construct($clientId, $clientSecret);
    }

    /**
     * @return string
     */
    public function baseUrl(): string
    {
        return 'https://api.sandbox.paypal.com';
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'sandbox';
    }
}
