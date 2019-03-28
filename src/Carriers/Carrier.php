<?php

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Shipment\Shipment;

class Carrier implements CarrierInterface
{

    const NAME = '';

    const CARRIERS = [
        SlovenskaPostaCarrier::NAME,
    ];

    protected $regex;
    protected $method;
    protected $url;
    protected $endPointClass;

    protected const REGEX = null;
    /** @var \MartinusDev\ShipmentsTracking\HttpClient\HttpClientInterface */
    protected $client;

    /** @var \MartinusDev\ShipmentsTracking\Endpoints\Endpoint */
    protected $endPoint;

    public static function load($carrierName): Carrier
    {
        $className = self::getNamespaceName($carrierName);

        return new $className();
    }

    public static function getNamespaceName($carrierName): string
    {
        if (!in_array($carrierName, self::CARRIERS)) {
            throw new \RuntimeException('Carrier ' . $carrierName . ' does not exists');
        }

        return __NAMESPACE__ . '\\' . $carrierName . 'Carrier';
    }

    public function __construct(array $options = [])
    {
        $options += [
            'client' => null,
        ];
        $this->client = $options['client'];
        if ($this->endPointClass) {
            $this->endPoint = new $this->endPointClass($options);
        }
    }

    /**
     * @param string $number
     * @return bool
     * @deprecated Nepotrebujem, staci detect()
     */
    public function checkNumber(string $number): bool
    {
        if (empty(static::REGEX)) {
            throw new \RuntimeException('Regex property missing in ' . get_class($this));
        }

        return preg_match(static::REGEX, $number);
    }

    public function getTrackingUrl(string $number): string
    {
        return preg_replace('/\$1/', $number, $this->url);
    }

    public function getShipment(string $number, $options = []): Shipment
    {
        $shipment = new Shipment([
            'carrierName' => static::NAME,
            'number' => $number,
            'carrier' => $this,
            'trackingLink' => $this->getTrackingUrl($number),
        ]);

        return $shipment;
    }

    public function getStates(Shipment $shipment): array
    {
        if (empty($this->endPoint)) {
            return [];
        }

        return $this->endPoint->getStates($shipment);
    }
}
