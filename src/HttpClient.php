<?php

namespace PayPal\Http;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HttpClient
{
    /**
     * Send the http request.
     *
     * @param  RequestInterface  $request
     * @return ResponseInterface
     * @throws GuzzleException|RequestException
     */
    public function send(RequestInterface $request): ResponseInterface;
}
