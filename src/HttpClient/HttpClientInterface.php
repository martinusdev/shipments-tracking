<?php

namespace MartinusDev\ShipmentsTracking\HttpClient;

interface HttpClientInterface
{
    /**
     * @param string $uri
     * @return string
     */
    public function get(string $uri):string;
}
