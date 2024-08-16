<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\PPLEndpoint;

class PPLCarrier extends Carrier
{
    public const NAME = 'PPL';

    // nepouziva sa
    public const REGEX = '/^PPL$/i';

    protected $endPointClass = PPLEndpoint::class;

    public function getTrackingUrl(string $number): string
    {
        return 'https://www.ppl.cz/vyhledat-zasilku?shipmentId=' . $number;
    }

}
