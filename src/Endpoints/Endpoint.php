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

    /**
     * @param string $statuses
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
     */
    abstract public function parseResponse(string $statuses): array;

    /**
     * @param \MartinusDev\ShipmentsTracking\Shipment\Shipment $shipment
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
     */
    public function getStates(Shipment $shipment)
    {
        $trackingUrl = $this->getUrl($shipment);
        $this->statuses = $this->fetchResponse($trackingUrl);

        return $this->parseResponse('');
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
