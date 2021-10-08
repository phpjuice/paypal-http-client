<?php

namespace PayPal\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Utils;
use PayPal\Http\Environment\Environment;

class PayPalClient implements HttpClient
{
    /**
     * Paypal environment (sandbox|production).
     *
     * @var Environment
     */
    protected Environment $environment;

    /**
     * Http client.
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Access Token.
     *
     * @var ?AccessToken
     */
    protected ?AccessToken $access_token;

    /**
     * HttpClient constructor. Pass the environment you wish to make calls to.
     * @param  Environment  $environment
     * @see Environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
        $this->client = new Client(['base_uri' => $environment->baseUrl()]);
        $this->access_token = null;
    }

    /**
     * Send http request.
     *
     * @param  Request  $request
     * @return Response
     * @throws GuzzleException
     * @throws RequestException
     */
    public function send(Request $request): Response
    {
        // if request doesn't have an authorization header
        if (!$this->hasAuthHeader($request)) {
            // fetch access token if null or expired
            if ($this->hasInvalidToken()) {
                $this->fetchAccessToken();
            }
            // add Authorization header to request
            $request = $request->withHeader('Authorization', $this->access_token->authorizationString());
        }

        // add user agent header to request
        $request = $this->injectUserAgentHeaders($request);

        // add sdk headers
        $request = $this->injectSdkHeaders($request);

        // add gzip headers
        $request = $this->injectGzipHeaders($request);

        // send request and return response
        return $this->client->send($request);
    }

    /**
     * Check if headers contain an auth header.
     *
     * @param  Request  $request
     * @return bool
     */
    public function hasAuthHeader(Request $request): bool
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
        $result = Utils::jsonDecode((string) $response->getBody());
        $this->access_token = new AccessToken($result->access_token, $result->token_type, $result->expires_in);

        return $this->access_token;
    }

    /**
     * Injects default user-agent into the request.
     *
     * @param  Request  $request
     * @return Request
     */
    public function injectUserAgentHeaders(Request $request): Request
    {
        return $request->withHeader('User-Agent', 'PayPalHttp-PHP HTTP/1.1');
    }

    /**
     * Inject PayPal sdk headers into request.
     *
     * @param  Request  $request
     * @return Request
     */
    public function injectSdkHeaders(Request $request): Request
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
     * @param  Request  $request
     * @return Request
     */
    public function injectGzipHeaders(Request $request): Request
    {
        return $request->withHeader('Accept-Encoding', 'gzip');
    }

    /**
     * Returns default user-agent.
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
