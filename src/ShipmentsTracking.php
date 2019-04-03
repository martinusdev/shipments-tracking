<?php

namespace MartinusDev\ShipmentsTracking;

use MartinusDev\ShipmentsTracking\Carriers\Carrier;
use MartinusDev\ShipmentsTracking\Carriers\CarrierInterface;
use MartinusDev\ShipmentsTracking\Carriers\UnknownCarrier;
use MartinusDev\ShipmentsTracking\HttpClient\GuzzleHttpClient;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;

class ShipmentsTracking
{
    /** @var \MartinusDev\ShipmentsTracking\HttpClient\HttpClientInterface */
    public static $client;
    protected $preferredCarriers = [];

    public function __construct($options = [])
    {
        $options += [
            'client' => null,
            'preferredCarriers' => [],
        ];
        if (empty($options['client'])) {
            $options['client'] = new GuzzleHttpClient();
        }
        self::$client = $options['client'];
        $this->preferredCarriers = $options['preferredCarriers'];
    }

    public function detectCarrier(string $number, $options = []): CarrierInterface
    {
        $options += [
            'preferredCarriers' => [],
        ];

        $carriers = Carrier::CARRIERS;
        $carriers = $this->sortWithPreferred($carriers, $options['preferredCarriers']);

        foreach ($carriers as $name) {
            $carrierNamespaceName = Carrier::getNamespaceName($name);
            $regex = constant($carrierNamespaceName . '::REGEX');
            if (preg_match($regex, $number)) {
                return new $carrierNamespaceName();
            }
        }

        return new UnknownCarrier();
    }

    public function getTrackingLink(string $number, array $options = []): string
    {
        $shipment = $this->get($number, $options);

        return $shipment->getTrackingLink();
    }

    public function get(string $number, array $options = []): Shipment
    {
        $carrier = $this->detectCarrier($number, $options);

        return $carrier->getShipment($number, $options);
    }

    /**
     * @param array $carriers
     * @param array $preferredCarriers
     * @return array
     */
    private function sortWithPreferred(array $carriers, array $preferredCarriers): array
    {
        $carriers = array_diff($carriers, $preferredCarriers);

        return $preferredCarriers + $carriers;
    }
}
