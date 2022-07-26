<?php

namespace PayPal\Http;

class AccessToken
{
    /**
     * Access token returned by PayPal.
     */
    protected string $token;

    /**
     * Access token type.
     *
     */
    protected string $token_type;

    /**
     * Time for access token to expires in seconds.
     */
    protected int $expires_in;

    /**
     * Time for creating access token to expires in seconds.
     */
    protected int $created_at;

    public function __construct(string $token, string $token_type, int $expires_in)
    {
        $this->token = $token;
        $this->token_type = $token_type;
        $this->expires_in = $expires_in;
        $this->created_at = time();
    }

    /**
     * Get the token.
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Get the token type.
     */
    public function getTokenType(): ?string
    {
        return $this->token_type;
    }

    /**
     * Returns authorization string.
     */
    public function authorizationString(): string
    {
        return $this->token_type.' '.$this->token;
    }

    /**
     * Determines if the token is expired or not.
     */
    public function isExpired(): bool
    {
        return time() >= ($this->created_at + $this->expires_in);
    }
}
