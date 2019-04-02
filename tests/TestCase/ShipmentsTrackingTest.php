<?php

namespace MartinusDev\ShipmentsTracking\Test;

use MartinusDev\ShipmentsTracking\Carriers\Carrier;
use MartinusDev\ShipmentsTracking\Carriers\UnknownCarrier;
use MartinusDev\ShipmentsTracking\HttpClient\GuzzleHttpClient;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\ShipmentsTracking;

class ShipmentsTrackingTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruct()
    {
        $shipmentsTracking = new ShipmentsTracking();

        $this->assertInstanceOf(ShipmentsTracking::class, $shipmentsTracking);
    }

    /**
     * @dataProvider detectCarrierDataProvider()
     * @param string $number
     * @param string $carrierName
     */
    public function testDetectCarrier($number, $carrierName)
    {
        $carrier = Carrier::load($carrierName, ['client' => new GuzzleHttpClient()]);

        $shipmentsTracking = new ShipmentsTracking();
        $detectedCarried = $shipmentsTracking->detectCarrier($number);
        $this->assertEquals($carrier, $detectedCarried);
    }

    /**
     * @dataProvider detectCarrierDataProvider()
     */
    public function testDetectCarrierUnknown()
    {
        $shipmentsTracking = new ShipmentsTracking();
        $detectedCarried = $shipmentsTracking->detectCarrier('abc');
        $this->assertInstanceOf(UnknownCarrier::class, $detectedCarried);
    }

    public function detectCarrierDataProvider()
    {
        return [
            ['RR079400152SK', 'SlovenskaPosta'],
        ];
    }

    public function testGet()
    {
        $shipmentsTracking = new ShipmentsTracking();
        $shipment = $shipmentsTracking->get('abc');
        $this->assertInstanceOf(Shipment::class, $shipment);
        $this->assertSame('{"number":"abc","carrierName":"Unknown","trackingLink":"","states":[]}', json_encode($shipment));
    }
}
