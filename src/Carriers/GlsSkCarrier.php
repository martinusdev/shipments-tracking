<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\GlsSkEndpoint;

class GlsSkCarrier extends Carrier
{
    public const NAME = 'GlsSk';

    public const REGEX = '/^((91|00)[0-9]{9})|(019[0-9]{9})$/i';

    protected $endPointClass = GlsSkEndpoint::class;

    /**
     * @var string
     */
    protected $method = 'GET';
    /**
     * @var string
     */
    protected $url = 'https://gls-group.eu/SK/sk/sledovanie-zasielok?match=$1';
}
