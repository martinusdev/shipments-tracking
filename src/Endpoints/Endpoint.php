<?php

namespace MartinusDev\ShipmentsTracking\Endpoints;

use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\ShipmentsTracking;

abstract class Endpoint
{
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
        $responseString = $this->fetchResponse($trackingUrl);

        return $this->parseResponse($responseString);
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
