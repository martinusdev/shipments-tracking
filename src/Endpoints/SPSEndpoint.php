<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints;

use Cake\Chronos\Chronos;
use MartinusDev\ShipmentsTracking\Endpoints\SPS\ParcelStatus;
use MartinusDev\ShipmentsTracking\Endpoints\SPS\SpsSoapClient;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\ReceivedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\UnknownState;
use RuntimeException;

class SPSEndpoint extends Endpoint
{
    /**
     * @var ParcelStatus[]
     */
    private $statuses;

    /**
     * @param string $responseString
     * @return State[]
     */
    public function parseResponse(string $responseString): array
    {
        $events = [];
        foreach ($this->statuses as $status) {
            $events[] = $this->parseEvent($status);
        }

        return $events;
    }

    public function parseEvent(ParcelStatus $status): State
    {
        $return = [];
        $return['date'] = Chronos::parse($status->getDate() . ' ' . $status->getTime(), 'Europe/Bratislava')->setTimezone('UTC');
        $return['description'] = $status->getStatus();
        $return['original'] = (array)$status;

        $stateClass = $this->getStateClass((int)$status->getStatusCodeX());

        return new $stateClass($return);
    }

    protected function getStateClass(int $eventState): string
    {
        switch ($eventState) {
            case 10:
            case 44:
                return ReceivedState::class;
            /*
             case 12:
                return NotifiedState::class;
            case 5:
                return DeliveredState::class;
            case 23:
                return ReturnedState::class;*/
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
        $client = new SpsSoapClient();
        $this->statuses = $client->getParcelStatus(703, 30, 28751101, 'E');

        return $this->parseResponse('');
    }

    public function fetchResponse(string $uri): string
    {
        throw new \Exception('Not implemented');
    }
}
