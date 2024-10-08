<?php

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Shipment\Shipment;

class Carrier implements CarrierInterface
{
    const NAME = '';

    const CARRIERS = [
        SlovenskaPostaCarrier::NAME,
        GlsSkCarrier::NAME,
        GlsCzCarrier::NAME,
        SPSCarrier::NAME,
        PacketaCarrier::NAME,
        PPLCarrier::NAME,
    ];

    protected $regex;
    protected $method;
    protected $url;
    protected $endPointClass;

    protected const REGEX = null;

    /**
     * @var ?\MartinusDev\ShipmentsTracking\Endpoints\Endpoint
     */
    protected $endPoint;
    /**
     * @var array<string>
     */
    protected $languages;

    /**
     * @param string $carrierName
     * @param array $options
     * @return \MartinusDev\ShipmentsTracking\Carriers\Carrier
     */
    public static function load($carrierName, array $options = []): Carrier
    {
        $className = self::getNamespaceName($carrierName);

        return new $className($options);
    }

    /**
     * @param string $carrierName
     * @return string
     */
    public static function getNamespaceName($carrierName): string
    {
        if (!in_array($carrierName, self::CARRIERS)) {
            throw new \RuntimeException('Carrier ' . $carrierName . ' does not exists');
        }

        return __NAMESPACE__ . '\\' . $carrierName . 'Carrier';
    }

    /**
     * Carrier constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $options += [
            'languages' => [],
        ];
        if ($this->endPointClass) {
            $this->endPoint = new $this->endPointClass($options);
        }
        $this->languages = $options['languages'];
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
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
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
     * @param array $options
     * @return \MartinusDev\ShipmentsTracking\Shipment\Shipment
     */
    public function getShipment(string $number, array $options = []): Shipment
    {
        $shipment = new Shipment([
            'carrierName' => $this->getName(),
            'number' => $number,
            'carrier' => $this,
            'trackingLink' => $this->getTrackingUrl($number),
        ], $options);

        return $shipment;
    }

    /**
     * @param \MartinusDev\ShipmentsTracking\Shipment\Shipment $shipment
     * @return array
     */
    public function getStates(Shipment $shipment): array
    {
        if (empty($this->endPoint)) {
            return [];
        }

        return $this->endPoint->getStates($shipment);
    }
}
