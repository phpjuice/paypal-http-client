<?php

namespace PayPal\Http\Environment;

class ProductionEnvironment extends PayPalEnvironment
{
    /**
     * @return string
     */
    public function baseUrl(): string
    {
        return 'https://api.paypal.com';
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'production';
    }
}
