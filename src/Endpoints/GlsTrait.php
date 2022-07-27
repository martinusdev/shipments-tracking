<?php
declare(strict_types=1);

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

trait GlsTrait
{
    /**
     * @param string $responseString
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
     * @throws \Exception
     */
    public function parseResponse(string $responseString): array
    {
        $response = new \SimpleXMLElement($responseString);
        $events = [];
        foreach ($response->Parcel->Statuses->Status as $status) {
            $attributes = [];
            foreach ($status->attributes() as $key => $value) {
                $attributes[$key] = (string)$value;
            }
            $events[] = $this->parseEvent($attributes);
        }

        return $events;
    }

    public function parseEvent(array $event): State
    {
        $event += [
            'StCode' => null,
            'StInfo' => '',
        ];
        $return = [];
        $return['date'] = Chronos::parse($event['StDate'], 'Europe/Bratislava')->setTimezone('UTC');

        $return['description'] = $event['StInfo'];
        $return['original'] = $event;

        $stateClass = $this->getStateClass((int)$event['StCode']);

        return new $stateClass($return);
    }

    protected function getStateClass(int $eventState): string
    {
        switch ($eventState) {
            case 1:
                return ReceivedState::class;
            case 12:
                return NotifiedState::class;
            case 5:
                return DeliveredState::class;
            case 23:
                return ReturnedState::class;
        }

        return UnknownState::class;
    }

    protected function getUrl(Shipment $shipment): string
    {
        $url = preg_replace('/\$1/', $shipment->number, $this->url);
        if (is_string($url)) {
            return $url;
        }

        throw new RuntimeException('Invalid $url type');
    }
}
