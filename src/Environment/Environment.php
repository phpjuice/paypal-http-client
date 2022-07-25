<?php

namespace PayPal\Http\Environment;

interface Environment
{
    public function name(): string;

    public function baseUrl(): string;

    public function basicAuthorizationString(): string;
}
