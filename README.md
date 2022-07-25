# PayPal HTTP Client

![Tests](https://github.com/phpjuice/paypal-http-client/workflows/Tests/badge.svg?branch=main)
[![Latest Stable Version](http://poser.pugx.org/phpjuice/paypal-http-client/v)](https://packagist.org/packages/phpjuice/paypal-http-client)
[![Total Downloads](http://poser.pugx.org/phpjuice/paypal-http-client/downloads)](https://packagist.org/packages/phpjuice/paypal-http-client)
[![License](http://poser.pugx.org/phpjuice/paypal-http-client/license)](https://packagist.org/packages/phpjuice/paypal-http-client)

This Package is a PHP Http Client. It provides a simple, fluent API to interact with PayPal rest API.

To learn all about it, head over to the extensive [documentation](https://phpjuice.gitbook.io/paypal-checkout-sdk).

## Installation

This Package requires PHP 7.4 or higher.

One can install this package via Composer.

```bash
composer require "phpjuice/paypal-http-client"
```

## Setup

PayPal HTTP Client is designed to simplify using the new PayPal checkout api in your app.

### Setup Credentials

Get client ID and client secret by visiting
the [PayPal Developer Account](https://developer.paypal.com/developer/applications) and
generating a REST API app.

### Setup Paypal Client

In order to communicate with PayPal platform we need to set up a client first :

#### Create a client with sandbox environment:

```php
<?php

use PayPal\Http\Environment\SandboxEnvironment;
use PayPal\Http\PayPalClient;

// client id and client secret retrieved from PayPal
$clientId = "<<PAYPAL-CLIENT-ID>>";
$clientSecret = "<<PAYPAL-CLIENT-SECRET>>";

// create a new sandbox environment
$environment = new SandboxEnvironment($clientId, $clientSecret);

// create a new client
$client = new PayPalClient($environment);
```

#### Create a client with production environment:

```php
<?php

use PayPal\Http\Environment\ProductionEnvironment;
use PayPal\Http\PayPalClient;

// client id and client secret retrieved from PayPal
$clientId = "<<PAYPAL-CLIENT-ID>>";
$clientSecret = "<<PAYPAL-CLIENT-SECRET>>";

// create a new sandbox environment
$environment = new ProductionEnvironment($clientId, $clientSecret);

// create a new client
$client = new PayPalClient($environment);
```

## Changelog

Please see the [CHANGELOG](changelog.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](./.github/CONTRIBUTING.md) for details and a todo list.

## Security

If you discover any security related issues, please email author instead of using the issue tracker.

## Credits

- [PayPal Docs](https://developer.paypal.com/docs/)
- [Gitbook](https://www.gitbook.com/)

## License

Please see the [Licence](./LICENSE) file.
