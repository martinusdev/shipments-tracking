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
        $defaultOptions = ['verify' => true];
        if (class_exists('Composer\CaBundle\CaBundle')) {
            $defaultOptions['verify'] = \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath();
        }
        $this->client = new Client($defaultOptions);
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
