<?php

namespace PayPal\Http\Environment;

abstract class PayPalEnvironment implements Environment
{
    /**
     * Paypal client id.
     */
    protected string $clientId;

    /**
     * PayPal client secret.
     */
    protected string $clientSecret;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function basicAuthorizationString(): string
    {
        return base64_encode($this->clientId.':'.$this->clientSecret);
    }
}
