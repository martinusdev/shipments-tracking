<?php

namespace MartinusDev\ShipmentsTracking\HttpClient;

interface HttpClientInterface
{
    public function get(string $uri):string;
}
