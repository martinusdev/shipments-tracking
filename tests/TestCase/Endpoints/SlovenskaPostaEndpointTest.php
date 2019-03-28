<?php

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints;

use MartinusDev\ShipmentsTracking\Endpoints\SlovenskaPostaEndpoint;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use MartinusDev\ShipmentsTracking\Test\TestSuite\StringCompareTrait;
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

    /**
     * @param array $event
     * @param string $stateName
     * @dataProvider parseEvent()
     */
    public function testParseEvent($event, $stateName)
    {
        $endpoint = new SlovenskaPostaEndpoint();
        $event += [
            'date' => [
                2019, 3, 16, 22, 18, 19,
            ],
            'desc' => [
                'sk' => 'Test state ' . $stateName,
            ],
            'post' => [
                'id' => 1,
                'name' => 'Bratislava',
            ],
        ];
        $state = $endpoint->parseEvent($event);
        $this->assertSame($stateName, (string)$state);
        $this->assertSame('Test state ' . $stateName, $state->description);
    }

    public function parseEvent()
    {
        return [
            [
                [
                    'state' => 'any_other',
                ],
                'unknown',
            ],
            [
                [
                    'state' => 'notified',
                ],
                'notified',
            ],
            [
                [
                    'state' => 'delivered',
                ],
                'delivered',
            ],
        ];
    }
}
