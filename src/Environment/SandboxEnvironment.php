<?php

namespace PayPal\Http\Environment;

class SandboxEnvironment extends PayPalEnvironment
{
    public function baseUrl(): string
    {
        return 'https://api.sandbox.paypal.com';
    }

    public function name(): string
    {
        return 'sandbox';
    }
}
