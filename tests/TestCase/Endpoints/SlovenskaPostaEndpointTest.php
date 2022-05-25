<?php

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints;

use MartinusDev\ShipmentsTracking\Endpoints\SlovenskaPostaEndpoint;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use PHPUnit\Framework\TestCase;

class SlovenskaPostaEndpointTest extends TestCase
{

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
        $this->assertSame('Zásielka uložená na pošte {post}', $deliveredState->description);
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
            'localDate' => '2019-03-16T22:18:19',
            'detailDescription' => 'Test state ' . $stateName,
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
                    'stateCode' => 'any_other',
                ],
                'unknown',
            ],
            [
                [
                    'stateCode' => 'notified',
                ],
                'notified',
            ],
            [
                [
                    'stateCode' => 'delivered',
                ],
                'delivered',
            ],
            [
                [
                    'stateCode' => 'returned',
                ],
                'returned',
            ],
        ];
    }
}
