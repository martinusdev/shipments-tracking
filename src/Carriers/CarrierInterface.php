<?php

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Shipment\Shipment;

interface CarrierInterface
{
    public function __construct(array $options = []);

    public function checkNumber(string $number):bool;

    public function getShipment(string $number, array $options): Shipment;

    public function getStates(Shipment $shipment): array;
}
