<?php

namespace PayPal\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Utils;
use PayPal\Http\Environment\Environment;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class PayPalClient implements HttpClient
{
    protected Environment $environment;

    protected Client $client;

    protected ?AccessToken $access_token;

    /**
     * HttpClient constructor. Pass the environment you wish to make calls to.
     * @param  Environment  $environment
     * @see Environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
        $this->client = new Client([
            'base_uri' => $environment->baseUrl(),
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);
        $this->access_token = null;
    }

    /**
     * Send http request.
     *
     * @param  RequestInterface  $request
     * @return ResponseInterface
     * @throws GuzzleException
     * @throws RequestException
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        // if request doesn't have an authorization header
        if (!$this->hasAuthHeader($request)) {
            // fetch access token if null or expired
            if ($this->hasInvalidToken()) {
                $this->fetchAccessToken();
            }

            // add Authorization header to request
            if ($this->access_token) {
                $request = $request->withHeader('Authorization', $this->access_token->authorizationString() ?? '');
            }
        }

        $request = $this->injectUserAgentHeaders($request);

        $request = $this->injectSdkHeaders($request);

        $request = $this->injectGzipHeaders($request);

        return $this->client->send($request);
    }

    /**
     * Check if headers contain an auth header.
     *
     * @param  RequestInterface  $request
     * @return bool
     */
    public function hasAuthHeader(RequestInterface $request): bool
    {
        return array_key_exists('Authorization', $request->getHeaders());
    }

    /**
     * Check if request has a valid token
     *
     * @return bool
     */
    public function hasInvalidToken(): bool
    {
        return !$this->access_token || $this->access_token->isExpired();
    }

    /**
     * Sends a request that fetches the access token.
     *
     * @return AccessToken
     * @throws GuzzleException
     */
    public function fetchAccessToken(): AccessToken
    {
        $response = $this->client->send(new AccessTokenRequest($this->environment));

        /** @var stdClass $result */
        $result = Utils::jsonDecode($response->getBody()->getContents());
        $this->access_token = new AccessToken($result->access_token, $result->token_type, $result->expires_in);

        return $this->access_token;
    }

    /**
     * Injects default user-agent into the request.
     *
     * @param  RequestInterface  $request
     * @return RequestInterface
     */
    public function injectUserAgentHeaders(RequestInterface $request): RequestInterface
    {
        return $request->withHeader('User-Agent', 'PayPalHttp-PHP HTTP/1.1');
    }

    /**
     * Inject PayPal sdk headers into request.
     *
     * @param  RequestInterface  $request
     * @return RequestInterface
     */
    public function injectSdkHeaders(RequestInterface $request): RequestInterface
    {
        $r = $request->withHeader('sdk_name', 'PayPal PHP SDK')
            ->withHeader('sdk_version', '1.0.0');

        /*
         * Only inject this header on production
         *
         * @see https://github.com/phpjuice/paypal-http-client/issues/6
         */
        if ('production' == $this->environment->name()) {
            $r = $r->withHeader('sdk_tech_stack', 'PHP '.PHP_VERSION);
        }

        return $r;
    }

    /**
     * Inject gzip headers into the request.
     *
     * @param  RequestInterface  $request
     * @return RequestInterface
     */
    public function injectGzipHeaders(RequestInterface $request): RequestInterface
    {
        return $request->withHeader('Accept-Encoding', 'gzip');
    }

    /**
     * Set custom Guzzle Http client.
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
