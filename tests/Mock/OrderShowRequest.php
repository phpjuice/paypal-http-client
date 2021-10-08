<?php

namespace Tests\Mock;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class OrderShowRequest extends Request implements RequestInterface
{
    public function __construct(string $order_id)
    {
        $headers = [
            'Prefer' => 'return=representation',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $uri = str_replace(':order_id', urlencode($order_id), '/v2/checkout/orders/:order_id');
        parent::__construct('GET', $uri, $headers);
    }
}
