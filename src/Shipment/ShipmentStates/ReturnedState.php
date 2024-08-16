<?php

namespace MartinusDev\ShipmentsTracking\Shipment\ShipmentStates;

/**
 * Status zasielky ze ona bola vratena odosielatelovi, klient ju neprevzial alebo odmietol
 */
class ReturnedState extends State
{
    protected $name = 'returned';
}
