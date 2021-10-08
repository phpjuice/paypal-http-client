<?php /** @noinspection SpellCheckingInspection */

namespace Tests\Http;

use PayPal\Http\AccessToken;

it('can create an access token', function () {
    $accessToken = new AccessToken('A21AAFSO5otrlVigoJUQ1p', 'Bearer', 32400);
    expect($accessToken->getToken())->toBe('A21AAFSO5otrlVigoJUQ1p');
    expect($accessToken->getTokenType())->toBe('Bearer');
});

it('checks if token is expired', function () {
    $accessToken = new AccessToken('A21AAFSO5otrlVigoJUQ1p', 'Bearer', 32400);
    expect($accessToken->isExpired())->toBeFalse();
    $accessToken = new AccessToken('A21AAFSO5otrlVigoJUQ1p', 'Bearer', 0);
    expect($accessToken->isExpired())->toBeTrue();
});

it('returns an authorization string', function () {
    $accessToken = new AccessToken('A21AAFSO5otrlVigoJUQ1p', 'Bearer', 32400);
    expect($accessToken->authorizationString())->toEqual('Bearer A21AAFSO5otrlVigoJUQ1p');
});
