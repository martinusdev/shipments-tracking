<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\GlsSkEndpoint;

class GlsSkCarrier extends Carrier
{
    public const NAME = 'GlsSk';

    protected $endPointClass = GlsSkEndpoint::class;

    /**
     * @var string
     */
    protected $method = 'GET';
    /**
     * @var string
     */
    protected $url = 'https://online.gls-slovakia.sk/tt_page.php?tt_value=$1';
}
