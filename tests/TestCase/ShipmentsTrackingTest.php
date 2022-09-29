<?php

namespace MartinusDev\ShipmentsTracking\Test\TestCase;

use MartinusDev\ShipmentsTracking\Carriers\GlsSkCarrier;
use MartinusDev\ShipmentsTracking\Carriers\UnknownCarrier;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\ShipmentsTracking;
use MartinusDev\ShipmentsTracking\Test\TestSuite\TestHttpClient;

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
    public function testDetectCarrier(string $number, string $carrierName)
    {
        $testClient = new TestHttpClient();
        $shipmentsTracking = new ShipmentsTracking(['client' => $testClient]);

        $detectedCarried = $shipmentsTracking->detectCarrier($number);

        $this->assertEquals($carrierName, $detectedCarried->getName());
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
            ['91090914000', 'GlsSk'],
            ['91110081000', 'GlsSk'],
            ['00104001000', 'GlsSk'],
            ['90333227133', 'GlsCz'],
            ['7030302875110113', 'SPS'],
            ['703-030-2875110113', 'SPS'],
            ['703-030-28751101', 'SPS'],
            ['Z4964561515', 'Packeta'],
        ];
    }

    public function testGet()
    {
        $shipmentsTracking = new ShipmentsTracking();
        $shipment = $shipmentsTracking->get('abc');
        $this->assertInstanceOf(Shipment::class, $shipment);
        $this->assertSame('{"number":"abc","carrierName":"Unknown","trackingLink":"","states":[]}', json_encode($shipment));
    }

    public function testGetWithCarrierOption()
    {
        $shipmentsTracking = new ShipmentsTracking();
        $shipment = $shipmentsTracking->get('abc', ['carrier' => GlsSkCarrier::NAME]);
        $this->assertInstanceOf(Shipment::class, $shipment);
        $this->assertSame('{"number":"abc","carrierName":"GlsSk","trackingLink":"https:\/\/gls-group.eu\/SK\/sk\/sledovanie-zasielok?match=abc","states":[]}', json_encode($shipment));
    }
}
