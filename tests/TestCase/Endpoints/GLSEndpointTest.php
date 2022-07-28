<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints;

use Exception;
use MartinusDev\ShipmentsTracking\Endpoints\GlsSkEndpoint;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use PHPUnit\Framework\TestCase;

class GLSEndpointTest extends TestCase
{
    public function testUnknownParcelNumber(): void
    {
        $endpoint = new GlsSkEndpoint();
        $this->expectException(Exception::class);
        $response = $endpoint->parseResponse('Err#02: Parcel number(s) not found in database');
        $this->assertNull($response);
    }

    public function testParseResponse(): void
    {
        $response = file_get_contents(COMPARISIONS . '/Endpoints/GLS/GLS_SK.xml');
        $endpoint = new GlsSkEndpoint();
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
        $endpoint = new GlsSkEndpoint();
        $event += [
            'StDate' => '2019-03-16T22:18:19',
            'StInfo' => 'Test state ' . $stateName,
        ];
        $state = $endpoint->parseEvent($event);
        $this->assertSame($stateName, (string)$state);
        $this->assertSame('Test state ' . $stateName, $state->description);
    }

    public function parseEvent(): array
    {
        return [
            [
                [
                    'StCode' => 51,
                ],
                'unknown',
            ],
            [
                [
                    'StCode' => 12,
                ],
                'notified',
            ],
            [
                [
                    'StCode' => 5,
                ],
                'delivered',
            ],
            [
                [
                    'StCode' => 23,
                ],
                'returned',
            ],
        ];
    }
}
