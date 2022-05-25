<?php

namespace MartinusDev\ShipmentsTracking\Endpoints;

use Cake\Chronos\Chronos;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\DeliveredState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\NotifiedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\ReceivedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\ReturnedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\UnknownState;
use RuntimeException;

class SlovenskaPostaEndpoint extends Endpoint
{
    public $url = 'https://api.posta.sk/tracking?q=$1';

    /**
     * @param string $responseString
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
     */
    public function parseResponse(string $responseString): array
    {
        $response = json_decode($responseString, true);
        if (!isset($response['results'])) {
            throw new \Exception('Unknown response: ' . json_encode($response));
        }
        if ($response['results'] === []) {
            return [];
        }
        $tracking = $response['results'][0];
        if (isset($tracking['events']) && is_array($tracking['events'])) {
            return $this->parseEvents($tracking['events']);
        }
        return [];
    }

    /**
     * @param array $events
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
     */
    protected function parseEvents(array $events): array
    {
        $states = [];
        foreach ($events as $event) {
            $state = $this->parseEvent($event);
            $states[] = $state;
        }

        return $states;
    }

    /**
     * @param array $event
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State
     */
    public function parseEvent($event): State
    {
        $event += [
            'stateCode' => null,
        ];
        $return = [];
        $return['date'] = Chronos::parse($event['localDate'], 'Europe/Bratislava')->setTimezone('UTC');

        $return['description'] = $event['detailDescription'];
        $return['original'] = $event;

        $stateClass = $this->getStateClass($event['stateCode']);

        return new $stateClass($return);
    }

    /**
     * @param string $eventState
     * @return string
     */
    protected function getStateClass($eventState)
    {
        switch ($eventState) {
            case 'received':
                return ReceivedState::class;
            case 'notified':
                return NotifiedState::class;
            case 'delivered':
                return DeliveredState::class;
            case 'returned':
                return ReturnedState::class;
        }

        return UnknownState::class;
    }

    /**
     * @param \MartinusDev\ShipmentsTracking\Shipment\Shipment $shipment
     * @return string
     */
    protected function getUrl(Shipment $shipment): string
    {
        $url = preg_replace('/\$1/', $shipment->number, $this->url);
        if (is_string($url)) {
            return $url;
        }

        throw new RuntimeException('Invalid $url type');
    }
}
