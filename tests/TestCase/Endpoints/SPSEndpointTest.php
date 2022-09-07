<?php

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints;

use MartinusDev\ShipmentsTracking\Carriers\SPSCarrier;
use MartinusDev\ShipmentsTracking\Endpoints\SPS\ParcelStatus;
use MartinusDev\ShipmentsTracking\Endpoints\SPSEndpoint;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use MartinusDev\ShipmentsTracking\ShipmentsTracking;
use PHPUnit\Framework\TestCase;

class SPSEndpointTest extends TestCase
{

    protected function setUp()
    {
        //  $testClient = new TestHttpClient(file_get_contents(COMPARISIONS . '/Endpoints/GLS/GLS_SK.xml'), 'https://online.gls-slovakia.sk/tt_page_xml.php?pclid=809558682');
        //  new ShipmentsTracking(['client' => $testClient]);
        new ShipmentsTracking();

        parent::setUp();
    }

    public function testGetStates(): void
    {
        $this->markTestSkipped('live request');
        $spsCarrier = new SPSCarrier();
        $endpoint = new SPSEndpoint();
        $shipment = new Shipment([
            'carrierName' => SPSCarrier::NAME,
            'number' => '703-030-28751101',
            'carrier' => $spsCarrier,
            // 'trackingLink' => $this->getTrackingUrl($number),
        ]);

        $states = $endpoint->getStates($shipment);
        $this->assertCount(2, $states);
    }

    public function testParseResponse(): void
    {
        $this->markTestSkipped('todo');
        $response = file_get_contents(COMPARISIONS . '/Endpoints/SPS/SPS.xml');
        $endpoint = new SPSEndpoint();
        $parsed = $endpoint->parseResponse($response);

        $this->assertCount(5, $parsed);
        /** @var State $deliveredState */
        $deliveredState = $parsed[0];
        $this->assertInstanceOf(State::class, $deliveredState);
        $this->assertSame('delivered', (string)$deliveredState);
        $this->assertSame('-', $deliveredState->description);
    }

    /**
     * @param array $event
     * @param string $stateName
     * @dataProvider parseEvent()
     */
    public function testParseEvent(array $event, string $stateName): void
    {
        $endpoint = new SPSEndpoint();
        $event += [
            'statusCode' => 0,
        ];
        $status = new ParcelStatus(0, '', '', '', $event['statusCode'], '', '', '', '');
        $state = $endpoint->parseEvent($status);
        $this->assertSame($stateName, (string)$state);
    }

    public function parseEvent(): array
    {
        return [
            [
                [
                    'statusCode' => 10,
                ],
                'received',
            ],
            [
                [
                    'statusCode' => 99,
                ],
                'unknown',
            ],
        ];
    }
}