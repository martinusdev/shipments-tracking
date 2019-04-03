<?php

namespace MartinusDev\ShipmentsTracking\Endpoints;

use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\ShipmentsTracking;

abstract class Endpoint
{
    /** @var \MartinusDev\ShipmentsTracking\HttpClient\HttpClientInterface */
    protected $client;

    public function __construct()
    {
    }

    abstract public function parseResponse($response): array;

    public function getStates(Shipment $shipment)
    {
        $trackingUrl = $this->getUrl($shipment);
        $response = $this->fetchResponse($trackingUrl);

        return $this->parseResponse($response);
    }

    public function fetchResponse($uri): string
    {
        return ShipmentsTracking::$client->get($uri);
    }

    protected function getUrl(Shipment $shipment)
    {
        return $shipment->getTrackingLink();
    }
}
