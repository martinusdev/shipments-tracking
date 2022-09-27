<?php

namespace MartinusDev\ShipmentsTracking\Endpoints\Packeta;

class PacketaSoapClient extends \SoapClient
{
    private static $classmap = [
        'StatusRecord' => '\MartinusDev\ShipmentsTracking\Endpoints\Packeta\StatusRecord',
        'StatusRecords' => '\MartinusDev\ShipmentsTracking\Endpoints\Packeta\StatusRecords',
    ];

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     */
    public function __construct(array $options = [], $wsdl = null)
    {
        foreach (self::$classmap as $key => $value) {
            if (!isset($options['classmap'][$key])) {
                $options['classmap'][$key] = $value;
            }
        }
        $options = array_merge([
            'features' => 1,
        ], $options);
        if (!$wsdl) {
            $wsdl = 'https://www.zasilkovna.cz/api/soap-php-bugfix.wsdl';
        }
        parent::__construct($wsdl, $options);
    }

    /**
     * @param string $apiPassword
     * @param string $packetId
     * @return ?StatusRecords
     */
    public function packetTracking(string $apiPassword, string $packetId)
    {
        return $this->__soapCall('packetTracking', [$apiPassword, $packetId]);
    }
}
