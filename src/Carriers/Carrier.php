<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Shipment\Shipment;

class Carrier implements CarrierInterface
{
    public const NAME = '';

    /** @var string[] */
    public const CARRIERS = [
        SlovenskaPostaCarrier::NAME,
    ];

    /**
     * @var string
     */
    protected $url;
    /**
     * @var string|\MartinusDev\ShipmentsTracking\Endpoints\Endpoint
     */
    protected $endPointClass;

    protected const REGEX = null;

    /**
     * @var \MartinusDev\ShipmentsTracking\Endpoints\Endpoint|null
     */
    protected $endPoint = null;

    /**
     * @param string $carrierName
     * @param array<string,mixed> $options
     * @return \MartinusDev\ShipmentsTracking\Carriers\Carrier
     */
    public static function load(string $carrierName, array $options = []): Carrier
    {
        /** @var \MartinusDev\ShipmentsTracking\Carriers\Carrier $className */
        $className = self::getNamespaceName($carrierName);

        return new $className($options);
    }

    /**
     * @param string $carrierName
     * @return string
     */
    public static function getNamespaceName(string $carrierName): string
    {
        if (!in_array($carrierName, self::CARRIERS)) {
            throw new \RuntimeException('Carrier ' . $carrierName . ' does not exists');
        }

        return __NAMESPACE__ . '\\' . $carrierName . 'Carrier';
    }

    /**
     * Carrier constructor.
     *
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = [])
    {
        $options += [];

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

        return (bool)preg_match(static::REGEX, $number);
    }

    /**
     * @param string $number
     * @return string
     */
    public function getTrackingUrl(string $number): string
    {
        $url = preg_replace('/\$1/', $number, $this->url);
        if (is_string($url)) {
            return $url;
        }

        throw new \RuntimeException('Invalid $url type');
    }

    /**
     * @param string $number
     * @param array<string,mixed> $options
     * @return \MartinusDev\ShipmentsTracking\Shipment\Shipment
     */
    public function getShipment(string $number, array $options = []): Shipment
    {
        $shipment = new Shipment([
            'carrierName' => static::NAME,
            'number' => $number,
            'carrier' => $this,
            'trackingLink' => $this->getTrackingUrl($number),
        ]);

        return $shipment;
    }

    /**
     * @param \MartinusDev\ShipmentsTracking\Shipment\Shipment $shipment
     * @return \MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State[]
     */
    public function getStates(Shipment $shipment): array
    {
        if (empty($this->endPoint)) {
            return [];
        }

        return $this->endPoint->getStates($shipment);
    }
}
