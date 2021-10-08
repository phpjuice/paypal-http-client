<?php /** @noinspection SpellCheckingInspection */

namespace Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Utils;
use PayPal\Http\AccessTokenRequest;
use PayPal\Http\Environment\ProductionEnvironment;
use PayPal\Http\Environment\SandboxEnvironment;
use PayPal\Http\PayPalClient;
use Tests\Mock\OrderShowRequest;

it("fetches access token", function () {
    $env = new ProductionEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);
    /** @noinspection PhpUnhandledExceptionInspection */
    $accessToken = $paypalClient->fetchAccessToken();
    expect($accessToken->authorizationString())->toBe('Bearer A21AAFSO5otrlVigoJUQ1p');
});

it("has authorization header", function () {
    $env = new ProductionEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);

    $request = new AccessTokenRequest($env);
    expect($paypalClient->hasAuthHeader($request))->toBeTrue();
});


it("has all sdk headers on production", function () {
    $env = new ProductionEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);

    $request = new AccessTokenRequest($env);
    $request = $paypalClient->injectSdkHeaders($request);
    expect($request->getHeaders())->toBe([
        'Authorization' => [
            'Basic Y2xpZW50X2lkOmNsaWVudF9zZWNyZXQ=',
        ],
        'Accept' => [
            'application/json',
        ],
        'Content-Type' => [
            'application/x-www-form-urlencoded',
        ],
        'sdk_name' => [
            'PayPal PHP SDK',
        ],
        'sdk_version' => [
            '1.0.0',
        ],
        'sdk_tech_stack' => [
            'PHP '.PHP_VERSION,
        ],
    ]);
});


it("has only a subset of sdk headers on sandbox", function () {
    $env = new SandboxEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);

    $request = new AccessTokenRequest($env);
    $request = $paypalClient->injectSdkHeaders($request);
    expect($request->getHeaders())->toBe([
        'Authorization' => [
            'Basic Y2xpZW50X2lkOmNsaWVudF9zZWNyZXQ=',
        ],
        'Accept' => [
            'application/json',
        ],
        'Content-Type' => [
            'application/x-www-form-urlencoded',
        ],
        'sdk_name' => [
            'PayPal PHP SDK',
        ],
        'sdk_version' => [
            '1.0.0',
        ],
    ]);
});


it("tests has invalid token method", function () {
    $env = new ProductionEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);

    expect($paypalClient->hasInvalidToken())->toBeTrue();

    /** @noinspection PhpUnhandledExceptionInspection */
    $paypalClient->fetchAccessToken();

    expect($paypalClient->hasInvalidToken())->toBeFalse();
});


it("can execute a request", function () {
    $env = new ProductionEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);

    $request = new OrderShowRequest('1KC5501443316171H');

    /** @noinspection PhpUnhandledExceptionInspection */
    $response = $paypalClient->send($request);

    $result = Utils::jsonDecode((string) $response->getBody(), true);
    expect($result['id'])->toBe('1KC5501443316171H');
});


beforeEach(function () {
    $response1 = json_encode([
        'access_token' => 'A21AAFSO5otrlVigoJUQ1p',
        'token_type' => 'Bearer',
        'expires_in' => 32400,
    ]);

    $response2 = json_encode([
        'id' => '1KC5501443316171H',
        'intent' => 'CAPTURE',
        'status' => 'CREATED',
    ]);

    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], $response1),
        new Response(200, ['Content-Type' => 'application/json'], $response2),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $this->client = new Client(['handler' => $handlerStack]);
});
