<?php

namespace PayPal\Http\Environment;

abstract class PayPalEnvironment implements Environment
{
    /**
     * Paypal client id.
     *
     * @var string
     */
    protected string $clientId;

    /**
     * PayPal client secret.
     *
     * @var string
     */
    protected string $clientSecret;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function basicAuthorizationString(): string
    {
        return base64_encode($this->clientId.':'.$this->clientSecret);
    }
}
