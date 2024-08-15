<?php

declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints;

use Cake\Chronos\Chronos;
use MartinusDev\ShipmentsTracking\Endpoints\PPL\PPLClient;
use MartinusDev\ShipmentsTracking\Endpoints\PPL\ShipmentEvent;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\DeliveredState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\NotifiedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\ReceivedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\ReturnedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\UnknownState;
use RuntimeException;

class PPLEndpoint extends Endpoint
{
    /**
     * @var \MartinusDev\ShipmentsTracking\Endpoints\PPL\ShipmentEvent[]
     */
    private $events = [];

    /**
     * @var \MartinusDev\ShipmentsTracking\Endpoints\PPL\ShipmentEvent
     */
    private $lastEvent;
    /**
     * @var PPLClient
     */
    private $client;


    public function __construct(PPLClient $client = null)
    {
        $this->client = $client ?? new PPLClient();
    }

    /**
     * @param string $responseString
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
     */
    public function parseResponse(string $responseString): array
    {
        $states = [];
        $states[] = $this->parseEvent($this->lastEvent);

        return $states;
    }

    public function parseEvent(ShipmentEvent $event): State
    {
        $return = [];
        $return['date'] = Chronos::parse($event->getEventDate(), 'Europe/Bratislava')->setTimezone('UTC');
        $return['description'] = $event->getName();
        $return['original'] = $event;

        $stateClass = $this->getStateClass($event->getCode());

        return new $stateClass($return);
    }

    protected function getStateClass(string $eventCode): string
    {
        switch ($eventCode) {
            case 'WaitingForShipment':
                return ReceivedState::class;
            case "Delivered.Parcelshop":
                return NotifiedState::class;
            case 'Delivered':
                return DeliveredState::class;
            case "Delivered.BackToSender":
                return ReturnedState::class;
        }

        return UnknownState::class;
    }

    protected function getUrl(Shipment $shipment): string
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @param int $shipmentNumber
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStates($shipmentNumber)
    {
        $this->events = $this->client->getShippingEvents($shipmentNumber);
        $this->lastEvent = end($this->events);

        return $this->parseResponse('');
    }

    public function fetchResponse(string $uri): string
    {
        throw new \Exception('Not implemented');
    }
}
