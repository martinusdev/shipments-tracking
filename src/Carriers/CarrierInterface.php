<?php

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;

interface CarrierInterface
{
    /**
     * CarrierInterface constructor.
     *
     * @param array<string,mixed> $options
     */
    public function __construct(array $options = []);

    /**
     * @param string $number
     * @return bool
     */
    public function checkNumber(string $number):bool;

    /**
     * @param string $number
     * @param array<string,mixed> $options
     * @return \MartinusDev\ShipmentsTracking\Shipment\Shipment
     */
    public function getShipment(string $number, array $options = []): Shipment;

    /**
     * @param \MartinusDev\ShipmentsTracking\Shipment\Shipment $shipment
     * @return State[]
     */
    public function getStates(Shipment $shipment): array;
}
