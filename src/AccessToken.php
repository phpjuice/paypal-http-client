<?php

namespace PayPal\Http;

class AccessToken
{
    /**
     * Access token returned by PayPal.
     *
     * @var string
     */
    protected string $token;

    /**
     * Access token type.
     *
     * @var string
     */
    protected string $token_type;

    /**
     * time for access token to expires in seconds.
     *
     * @var int
     */
    protected int $expires_in;

    /**
     * time for creating access token to expires in seconds.
     *
     * @var int
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
     * gets the token.
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * gets the token.
     */
    public function getTokenType(): ?string
    {
        return $this->token_type;
    }

    /**
     * returns authorization string.
     */
    public function authorizationString(): string
    {
        return $this->token_type.' '.$this->token;
    }

    /**
     * returns if a token is expired or not.
     */
    public function isExpired(): bool
    {
        return time() >= $this->created_at + $this->expires_in;
    }
}
