<?php

namespace PayPal\Http\Environment;

class ProductionEnvironment extends PayPalEnvironment
{
    public function baseUrl(): string
    {
        return 'https://api.paypal.com';
    }

    public function name(): string
    {
        return 'production';
    }
}
