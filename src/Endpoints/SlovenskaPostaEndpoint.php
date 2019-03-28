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
    //https://api.posta.sk/private/search?m=tnt&q=ZB061788670SK,ZB061788666SK
    public $url = 'https://api.posta.sk/private/search?m=tnt&q=$1';

    public function parseResponse($responseString): array
    {
        $response = json_decode($responseString, true);
        $tracking = $response['parcels'][0];
        $events = $tracking['events'];
        $states = $this->parseEvents($events);

        return $states;
    }

    protected function parseEvents($events):array
    {
        $states = [];
        foreach ($events as $event) {
            $state = $this->parseEvent($event);
            $states[] = $state;
        }

        return $states;
    }

    protected function parseEvent($event): State
    {
        $return = [
            'date' => null,
            'description' => null,
            'original' => [],
        ];
        $return['date'] = Chronos::create($event['date'][0], $event['date'][1], $event['date'][2], $event['date'][3], $event['date'][4], $event['date'][5], 'Europe/Bratislava')->setTimezone('UTC');

        $return['description'] = preg_replace('/{post}/', $event['post']['name'], $event['desc']['sk']);
        $return['original'] = $event;

        $stateClass = $this->getStateClass($event['state']);

        return new $stateClass($return);
    }

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

    protected function getUrl(Shipment $shipment)
    {
        return preg_replace('/\$1/', $shipment->number, $this->url);
    }
}
