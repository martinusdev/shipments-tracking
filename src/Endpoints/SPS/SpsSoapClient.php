<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints\SPS;

class SpsSoapClient extends \SoapClient
{
    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = [
        'ParcelStatus' => '\MartinusDev\ShipmentsTracking\Endpoints\SPS\ParcelStatus',
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
            $wsdl = 'https://t-t.sps-sro.sk/service_soap.php?wsdl';
        }
        parent::__construct($wsdl, $options);
    }

    /**
     * @param int $landnr
     * @param int $mandnr
     * @param int $lfdnr
     * @param string $langi
     * @return \MartinusDev\ShipmentsTracking\Endpoints\SPS\ParcelStatus[]
     */
    public function getParcelStatus($landnr, $mandnr, $lfdnr, $langi)
    {
        return $this->__soapCall('getParcelStatus', [$landnr, $mandnr, $lfdnr, $langi]);
    }
}
