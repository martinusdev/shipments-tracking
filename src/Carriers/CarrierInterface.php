<?php

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Shipment\Shipment;

interface CarrierInterface
{
    /**
     * CarrierInterface constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = []);

    /**
     * @param string $number
     * @return bool
     */
    public function checkNumber(string $number):bool;

    /**
     * @param string $number
     * @param array $options
     * @return \MartinusDev\ShipmentsTracking\Shipment\Shipment
     */
    public function getShipment(string $number, array $options): Shipment;

    /**
     * @param \MartinusDev\ShipmentsTracking\Shipment\Shipment $shipment
     * @return array
     */
    public function getStates(Shipment $shipment): array;
}
