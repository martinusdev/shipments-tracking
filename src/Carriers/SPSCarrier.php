<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\SPSEndpoint;

class SPSCarrier extends Carrier
{
    public const NAME = 'SPS';

    public const REGEX = '/^([0-9]{3})-?([0-9]{3})-?([0-9]{8})([0-9]{2})?$/i';

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
        return preg_replace(self::REGEX, 'https://t-t.sps-sro.sk/result.php/?cmd=SDG_SEARCH&sprache=SK&sdg_landnr=$1&sdg_mandnr=$2&sdg_lfdnr=$3', $number);
    }
}
