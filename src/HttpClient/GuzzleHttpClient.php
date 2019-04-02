<?php

namespace MartinusDev\ShipmentsTracking\HttpClient;

use GuzzleHttp\Client;

class GuzzleHttpClient implements HttpClientInterface
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    /**
     * GuzzleHttpClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param string $uri
     * @return string
     */
    public function get(string $uri):string
    {
        return (string)$this->client->get($uri);
    }
}
