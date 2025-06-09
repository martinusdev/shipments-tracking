<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use MartinusDev\ShipmentsTracking\Carriers\SPSCarrier;
use MartinusDev\ShipmentsTracking\Endpoints\SPSEndpoint;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use PHPUnit\Framework\TestCase;

class SPSEndpointTest extends TestCase
{
    public function testGetStates(): void
    {
        $response = file_get_contents(COMPARISIONS . '/Endpoints/SPS/response.json');
        $mock = new MockHandler([
            new Response(200, [], $response),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $spsCarrier = new SPSCarrier();
        $shipment = new Shipment([
            'carrierName' => SPSCarrier::NAME,
            'number' => 'SK0S9X123456789P3T',
            'carrier' => $spsCarrier,
        ], ['loadStates' => false]);

        $spsEndpoint = new SPSEndpoint(['client' => $client]);
        $states = $spsEndpoint->getStates($shipment);

        $this->assertCount(12, $states);
        $this->assertSame('delivered', (string)$states[0]);
    }
}
