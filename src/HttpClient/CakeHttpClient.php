<?php

namespace MartinusDev\ShipmentsTracking\HttpClient;

use Cake\Http\Client;

class CakeHttpClient extends AbstractHttpClient implements HttpClientInterface
{
    /** @var Client */
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
    public function get(string $uri): string
    {
        return (string)$this->client->get($uri)->getBody();
    }
}
