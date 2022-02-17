<?php

namespace PayPal\Http\Environment;

class SandboxEnvironment extends PayPalEnvironment
{
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
