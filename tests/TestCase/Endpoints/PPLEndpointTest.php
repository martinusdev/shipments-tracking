<?php

declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use MartinusDev\ShipmentsTracking\Endpoints\GlsSkEndpoint;
use MartinusDev\ShipmentsTracking\Endpoints\PPL\PPLClient;
use MartinusDev\ShipmentsTracking\Endpoints\PPLEndpoint;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use PHPUnit\Framework\TestCase;

class PPLEndpointTest extends TestCase
{

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testResponse(): void
    {
        $authResponse = file_get_contents(COMPARISIONS . '/Endpoints/PPL/authResponse.json');
        $response = file_get_contents(COMPARISIONS . '/Endpoints/PPL/response.json');

        $mock = new MockHandler([
            new Response(200, [], $authResponse),
            new Response(201, [], $response),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new PPLClient(['handler' => $handlerStack]);
        $endpoint = new PPLEndpoint($client);


        $states = $endpoint->getStates(00000000001);

        $this->assertCount(1, $states);
        $this->assertSame('delivered', (string)$states[0]);
    }
}
