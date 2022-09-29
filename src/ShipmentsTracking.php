<?php

namespace MartinusDev\ShipmentsTracking;

use MartinusDev\ShipmentsTracking\Carriers\Carrier;
use MartinusDev\ShipmentsTracking\Carriers\CarrierInterface;
use MartinusDev\ShipmentsTracking\Carriers\UnknownCarrier;
use MartinusDev\ShipmentsTracking\HttpClient\GuzzleHttpClient;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;

class ShipmentsTracking
{
    /**
     * @var \MartinusDev\ShipmentsTracking\HttpClient\HttpClientInterface
     */
    public static $client;
    /**
     * @var string[]
     */
    protected $preferredCarriers = [];

    /**
     * @param array<string,mixed> $options
     */
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

    /**
     * @param string $number
     * @param array<string,array<string,mixed>> $options
     * @return \MartinusDev\ShipmentsTracking\Carriers\CarrierInterface
     */
    public function detectCarrier(string $number, array $options = []): CarrierInterface
    {
        $options += [
            'preferredCarriers' => [],
        ];

        $carriers = Carrier::CARRIERS;
        $carriers = $this->sortWithPreferred($carriers, $options['preferredCarriers']);

        foreach ($carriers as $name) {
            /** @var string|\MartinusDev\ShipmentsTracking\Carriers\CarrierInterface $carrierNamespaceName */
            $carrierNamespaceName = Carrier::getNamespaceName($name);
            /** @var string $regex */
            $regex = constant($carrierNamespaceName . '::REGEX');
            if (preg_match($regex, $number)) {
                /** @var \MartinusDev\ShipmentsTracking\Carriers\CarrierInterface $carrier */
                $carrier = new $carrierNamespaceName();

                return $carrier;
            }
        }

        return new UnknownCarrier();
    }

    /**
     * @param string $number
     * @param array<string,mixed> $options
     * @return string
     */
    public function getTrackingLink(string $number, array $options = []): string
    {
        $shipment = $this->get($number, $options);

        return $shipment->getTrackingLink();
    }

    /**
     * @param string $number
     * @param array<string,mixed> $options
     * @return \MartinusDev\ShipmentsTracking\Shipment\Shipment
     */
    public function get(string $number, array $options = []): Shipment
    {
        if (isset($options['carrier'])) {
            $carrier = $this->getCarrierByName($options['carrier']);
        } else {
            $carrier = $this->detectCarrier($number, $options);
        }

        return $carrier->getShipment($number, $options);
    }

    private function getCarrierByName(string $name): CarrierInterface
    {
        if (!in_array($name, Carrier::CARRIERS)) {
            return new UnknownCarrier();
        }

        /** @var string|\MartinusDev\ShipmentsTracking\Carriers\CarrierInterface $carrierNamespaceName */
        $carrierNamespaceName = Carrier::getNamespaceName($name);
        /** @var \MartinusDev\ShipmentsTracking\Carriers\CarrierInterface $carrier */
        return new $carrierNamespaceName();
    }

    /**
     * @param string[] $carriers
     * @param string[] $preferredCarriers
     * @return string[]
     */
    private function sortWithPreferred(array $carriers, array $preferredCarriers): array
    {
        $carriers = array_diff($carriers, $preferredCarriers);

        return $preferredCarriers + $carriers;
    }
}
