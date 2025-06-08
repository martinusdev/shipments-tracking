<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\SPSEndpoint;
use MartinusDev\ShipmentsTracking\Endpoints\SPS\SPSParcelNumber;

class SPSCarrier extends Carrier
{
    public const NAME = 'SPS';

    public const REGEX = '/^SK[0-9A-Z]{15,}$/i';
    public const REGEX_V1 = '/^([0-9]{3})-?([0-9]{3})-?([0-9]{8})([0-9]{2})?$/i'; // v1 format, deprecated 2025-06-01

    protected $endPointClass = SPSEndpoint::class;

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @param string $number
     * @return string
     */
    public function getTrackingUrl(string $number): string
    {
        $spsNumber = new SPSParcelNumber($number);
        $baseUrl = 'https://t-t.sps-sro.sk/result.php/?cmd=SDG_SEARCH&sprache=SK';

        if ($spsNumber->version === 2) {
            return $baseUrl . '&sdg_lfdnr=' . $spsNumber->lfdNr;
        } elseif ($spsNumber->version === 1) {
            return preg_replace(self::REGEX_V1, $baseUrl . '&sdg_landnr=$1&sdg_mandnr=$2&sdg_lfdnr=$3', $number);
        }

        return '';
    }
}
