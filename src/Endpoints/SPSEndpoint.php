<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints;

use Cake\Chronos\Chronos;
use MartinusDev\ShipmentsTracking\Endpoints\SPS\ParcelStatus;
use MartinusDev\ShipmentsTracking\Endpoints\SPS\SPSParcelNumber;
use MartinusDev\ShipmentsTracking\Endpoints\SPS\SPSSoapClient;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\DeliveredState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\NotifiedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\ReceivedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\ReturnedState;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\UnknownState;
use RuntimeException;

class SPSEndpoint extends Endpoint
{
    /**
     * @var \MartinusDev\ShipmentsTracking\Endpoints\SPS\ParcelStatus[]
     */
    private $statuses = [];
    /**
     * @var \SoapClient
     */
    private $client;

    public function __construct()
    {
        $this->client = new SPSSoapClient();
    }

    public function setClient(\SoapClient $client): void
    {
        $this->client = $client;
    }

    /**
     * @param string $responseString
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
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
            case 71:   //Deposit in Parcel Shop
                return NotifiedState::class;
            case 40:
            case 42:
                return DeliveredState::class;
            case 260:   //Return to Shipper
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
        $SPSParcelNumber = new SPSParcelNumber($shipment->number);
        $this->statuses = $this->client->getParcelStatus((int)$SPSParcelNumber->landNr, (int)$SPSParcelNumber->manNr, (int)$SPSParcelNumber->lfdNr, 'E');

        return $this->parseResponse('');
    }

    public function fetchResponse(string $uri): string
    {
        throw new \Exception('Not implemented');
    }
}
