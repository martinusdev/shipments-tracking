<?php

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Shipment;

use MartinusDev\ShipmentsTracking\Carriers\UnknownCarrier;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use PHPUnit\Framework\TestCase;

class ShipmentTest extends TestCase
{

    public function testConstruct()
    {
        $shipment = new Shipment([
            'number' => 'abc', 'trackingLink' => 'http://www.example.com/tracking/abc',
            'carrierName' => UnknownCarrier::NAME,
            'carrier' => new UnknownCarrier(),
        ]);
        $this->assertSame('abc', $shipment->number);
        $this->assertSame('http://www.example.com/tracking/abc', $shipment->trackingLink);

        //string
        $this->assertSame('abc', (string)$shipment);
        //json
        $this->assertSame('{"number":"abc","carrierName":"Unknown","trackingLink":"http:\/\/www.example.com\/tracking\/abc","states":[]}', json_encode($shipment));
    }
}
