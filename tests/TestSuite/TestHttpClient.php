<?php

namespace MartinusDev\ShipmentsTracking\Test\TestSuite;

use MartinusDev\ShipmentsTracking\HttpClient\HttpClientInterface;
use PHPUnit\Framework\Assert;

class TestHttpClient extends Assert implements HttpClientInterface
{
    private $assertCalls;
    private $responses;

    public function __construct($responses = [], $assertCalls = null)
    {
        if (is_string($assertCalls)) {
            $assertCalls = [$assertCalls];
        }
        if (is_string($responses)) {
            $responses = [$responses];
        }
        $this->assertCalls = $assertCalls;
        $this->responses = $responses;
    }

    public function get(string $uri): string
    {
        if (!is_null($this->assertCalls)) {
            $this->assertNotEmpty($this->assertCalls, 'get() is called, but no assers left');
            $assertKey = key($this->assertCalls);
            $assert = $this->assertCalls[$assertKey];
            unset($this->assertCalls[$assertKey]);
            $this->assertSame($assert, $uri);
        }

        $responseKey = key($this->responses);
        $response = $this->responses[$responseKey];
        unset($this->responses[$responseKey]);

        return $response;
    }
}
