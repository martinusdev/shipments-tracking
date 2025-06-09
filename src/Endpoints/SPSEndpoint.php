<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints;

use Cake\Chronos\Chronos;
use GuzzleHttp\Client;
use MartinusDev\ShipmentsTracking\Endpoints\SPS\ParcelStatus;
use MartinusDev\ShipmentsTracking\Endpoints\SPS\SPSParcelNumber;
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
     * @var \GuzzleHttp\Client
     */
    private Client $client;
    private string $apiKey;
    private string $apiUrl;

    /**
     * @param array{client?: \GuzzleHttp\Client, apiKey?: string, apiUrl?: string} $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->apiKey = $this->options['apiKey'] ?? '';
        $this->apiUrl = $this->options['apiUrl'] ?? 'https://trackandtraceapi.nesy.sps-sro.sk/api/sk/v1/TrackAndTrace';
        $this->client = $this->options['client'] ?? new Client();
    }

    public function getParcelStatus(SPSParcelNumber $parcel, string $language): string
    {
        $requestData = json_encode([
            'langi' => $language,
            'landnr' => $parcel->landNr,
            'mandnr' => $parcel->manNr,
            'lfdnr' => $parcel->lfdNr,
        ]);

        if ($requestData === false) {
            throw new \RuntimeException('Failed to encode request data: ' . json_last_error_msg());
        }

        $response = $this->client->post(
            $this->apiUrl . '/GetParcelStatus?apikey=' . $this->apiKey,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => $requestData,
            ]
        );

        return $response->getStatusCode() === 200 ? $response->getBody()->getContents() : '';
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

    /**
     * @param string $responseString
     * @return ParcelStatus[]
     */
    private function parseStatusJson(string $responseString): array
    {
        $response = json_decode($responseString, true);
        if (!is_array($response) || $response['resultCode'] !== 200 || !is_array($response['payload'])) {
            return [];
        }

        $statuses = [];
        foreach ($response['payload'] as $status) {
            $statuses[] = ParcelStatus::fromArray($status);
        }

        return $statuses;
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
            case 71: // Deposit in Parcel Shop
                return NotifiedState::class;
            case 40:
            case 42:
                return DeliveredState::class;
            case 260: // Return to Shipper
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
        $responseString = $this->getParcelStatus($SPSParcelNumber, 'EN');
        $this->statuses = $this->parseStatusJson($responseString);

        return $this->parseResponse('');
    }

    public function fetchResponse(string $uri): string
    {
        throw new \Exception('Not implemented');
    }
}
