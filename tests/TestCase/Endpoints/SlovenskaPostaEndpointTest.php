<?php

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints;

use MartinusDev\ShipmentsTracking\Endpoints\SlovenskaPostaEndpoint;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use MartinusDev\ShipmentsTracking\Test\TestSuite\StringCompareTrait;
use MartinusDev\ShipmentsTracking\Test\TestSuite\TestHttpClient;
use PHPUnit\Framework\TestCase;

class SlovenskaPostaEndpointTest extends TestCase
{
    use StringCompareTrait;


    public function testParseResponse()
    {
        $response = file_get_contents(COMPARISIONS . '/Endpoints/SlovenskaPosta/response_1.json');
        $endpoint = new SlovenskaPostaEndpoint();
        $parsed = $endpoint->parseResponse($response);

        $this->assertCount(3, $parsed);
        /** @var State $deliveredState */
        $deliveredState = $parsed[2];
        $this->assertInstanceOf(State::class, $deliveredState);
        $this->assertSame('delivered', (string)$deliveredState);
        $this->assertSame('Zásielka vydaná adresátovi na pošte Bratislava 1', $deliveredState->description);

    }
}
