<?php

namespace MartinusDev\ShipmentsTracking\Shipment\ShipmentStates;

/**
 * Ostatne statusy zasielky ktore maju dopravcovia ale nesledujeme ich
 */
class UnknownState extends State
{
    protected $name = 'unknown';
}
