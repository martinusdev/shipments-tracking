<?php

namespace MartinusDev\ShipmentsTracking\Endpoints;

use Cake\Chronos\Chronos;
use MartinusDev\ShipmentsTracking\Endpoints\Packeta\PacketaSoapClient;
use MartinusDev\ShipmentsTracking\Endpoints\Packeta\StatusRecord;
use MartinusDev\ShipmentsTracking\Endpoints\Packeta\StatusRecords;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\DeliveredState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\NotifiedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\ReceivedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\ReturnedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\UnknownState;
use RuntimeException;

class PacketaEndpoint extends Endpoint
{

    /**
     * @var StatusRecord[]
     */
    private $statuses = [];
    /**
     * @var \SoapClient
     */
    private $client;

    public function __construct()
    {
        $this->client = new PacketaSoapClient();
    }

    public function parseResponse(string $responseString): array
    {
        $events = [];
        foreach ($this->statuses as $status) {
            $events[] = $this->parseEvent($status);
        }

        return $events;
    }

    public function setClient(\SoapClient $client): void
    {
        $this->client = $client;
    }

    public function parseEvent(StatusRecord $status): State
    {
        $return = [];
        $return['date'] = Chronos::parse($status->dateTime, 'Europe/Bratislava')->setTimezone('UTC');
        $return['description'] = $status->codeText;
        $return['original'] = (array)$status;

        $stateClass = $this->getStateClass((int)$status->statusCode);

        return new $stateClass($return);
    }

    protected function getStateClass(int $eventState): string
    {
        switch ($eventState) {
            case 2: //arrived
                return ReceivedState::class;
            case 5: //ready for pickup
                return NotifiedState::class;
            case 7://delivered
                return DeliveredState::class;
            case 10:
                return ReturnedState::class;
        }

        return UnknownState::class;
    }

    protected function getUrl(Shipment $shipment): string
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @param \MartinusDev\ShipmentsTracking\Shipment\Shipment $shipment
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
     */
    public function getStates(Shipment $shipment)
    {
        $statusRecords = $this->client->packetTracking(getenv('PACKETA_API_PASSWORD'), $shipment->number);
        if (!($statusRecords instanceof StatusRecords)) {
            throw new \InvalidArgumentException('Incorrect response: ' . json_encode($statusRecords));
        }
        $this->statuses = $statusRecords->record;

        return $this->parseResponse('');
    }

    public function fetchResponse(string $uri): string
    {
        throw new \Exception('Not implemented');
    }
}