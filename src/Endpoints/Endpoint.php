<?php

namespace MartinusDev\ShipmentsTracking\Endpoints;

use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use MartinusDev\ShipmentsTracking\ShipmentsTracking;

abstract class Endpoint
{
    /** @var \MartinusDev\ShipmentsTracking\HttpClient\HttpClientInterface */
    protected $client;

    public function __construct()
    {
    }

    /**
     * @param string $responseString
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
     */
    abstract public function parseResponse(string $responseString): array;

    /**
     * @param \MartinusDev\ShipmentsTracking\Shipment\Shipment $shipment
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
     */
    public function getStates(Shipment $shipment)
    {
        $trackingUrl = $this->getUrl($shipment);
        $response = $this->fetchResponse($trackingUrl);

        return $this->parseResponse($response);
    }

    public function fetchResponse(string $uri): string
    {
        return ShipmentsTracking::$client->get($uri);
    }

    protected function getUrl(Shipment $shipment): string
    {
        return $shipment->getTrackingLink();
    }
}
