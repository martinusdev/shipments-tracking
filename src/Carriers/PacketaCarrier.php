<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\PacketaEndpoint;

class PacketaCarrier extends Carrier
{
    public const NAME = 'Packeta';

    public const REGEX = '/^(Z[0-9]{10})$/i';

    protected $endPointClass = PacketaEndpoint::class;

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
        return preg_replace(self::REGEX, 'https://tracking.packeta.com/en/?id=$1', $number);
    }
}
