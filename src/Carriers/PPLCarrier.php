<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\PPLEndpoint;

class PPLCarrier extends Carrier
{
    public const NAME = 'PPL';

    public const REGEX = '/^([0-9]+)$/i';

    protected $endPointClass = PPLEndpoint::class;

    public function getTrackingUrl(string $number): string
    {
        $trackingUrl = 'https://www.ppl.cz/vyhledat-zasilku?shipmentId=$1';

        return preg_replace(self::REGEX, $trackingUrl, $number);
    }

}
