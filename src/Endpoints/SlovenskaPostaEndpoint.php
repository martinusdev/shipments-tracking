<?php

namespace MartinusDev\ShipmentsTracking\Endpoints;

use Cake\Chronos\Chronos;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\DeliveredState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\NotifiedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\ReceivedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\UnknownState;

class SlovenskaPostaEndpoint extends Endpoint
{
    public $url = 'https://api.posta.sk/private/search?m=tnt&q=$1';

    /**
     * @param string $responseString
     * @return array
     */
    public function parseResponse($responseString): array
    {
        $response = json_decode($responseString, true);
        $tracking = $response['parcels'][0];
        $events = $tracking['events'];
        $states = $this->parseEvents($events);

        return $states;
    }

    /**
     * @param array $events
     * @return array
     */
    protected function parseEvents($events): array
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
        $return = [
            'date' => null,
            'description' => null,
            'original' => [],
        ];
        $return['date'] = Chronos::create($event['date'][0], $event['date'][1], $event['date'][2], $event['date'][3], $event['date'][4], $event['date'][5], 'Europe/Bratislava')->setTimezone('UTC');

        $return['description'] = preg_replace('/{post}/', $event['post']['name'], $event['desc']['sk']);
        $return['original'] = $event;

        $stateClass = $this->getStateClass($event['state'] ?? null);

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

        throw new \RuntimeException('Invalid $url type');
    }
}
