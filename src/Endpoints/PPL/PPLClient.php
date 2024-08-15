<?php

declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints\PPL;

use GuzzleHttp\Client;

class PPLClient
{
    private $client;
    private $authUrl = 'https://api.dhl.com/ecs/ppl/myapi2';
    private $clientId;
    private $clientSecret;
    private $accessToken;
    private $handler;

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct($options = [])
    {
        $this->setCredentials();
        $this->handler = isset($options['handler']) ?? [];
    }

    /**
     * @param string $shipmentNumber
     * @return array<ShipmentEvent>
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getShippingEvents(string $shipmentNumber): array
    {
        $this->accessToken = $this->accessToken ?? $this->getAccessToken();
        $res = $this->client->get($this->authUrl . '/shipment', [
            'query' => [
                'limit' => 1,
                'offset' => 0,
                'shipmentNumbers' => (int)$shipmentNumber,
            ],
            'headers' => ['Authorization' => $this->accessToken],
        ]);
        $info = $this->getJsonResponse($res);
        $events = [];
        if ($info[0]['shipmentNumber'] == $shipmentNumber) { //overenie ci mame spravnu zasielku v response
            foreach ($info[0]['trackAndTrace']['events'] as $event) {
                if (isset($event['code'])) { //ak je code podla ktoreho ide identifikacia stavu shipmentu
                    $events[] = new ShipmentEvent(
                        $event['statusId'],
                        $event['code'],
                        $event['phase'],
                        $event['group'],
                        $event['eventDate'],
                        $event['name']
                    );
                }
            }
        }

        return $events;
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getAccessToken(): void
    {
        $body = [
            'grant_type' => 'client_credentials',
            'scope' => 'myapi2',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];
        $this->client = new Client($this->handler);
        $res = $this->client->post($this->authUrl . '/login/getAccessToken', [
            'form_params' => $body,
        ]);
        $response = $this->getJsonResponse($res);

        if ($response !== []) {
            $this->accessToken = $response['token_type'] . ' ' . $response['access_token'];
        }
    }

    /**
     * Set client_id and client_secret from env parameters
     *
     * @return void
     */
    private function setCredentials()
    {
        $this->clientId = getenv('PPL_API_CLIENT_ID');
        $this->clientSecret = getenv('PPL_API_CLIENT_SECRET');
    }

    private function getJsonResponse($res)
    {
        return json_decode($res->getBody()->getContents(), true) ?? [];
    }
}
