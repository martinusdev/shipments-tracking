<?php

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\GlsCzEndpoint;

class GlsCzCarrier extends Carrier
{
    public const NAME = 'GlsCz';

    protected $endPointClass = GlsCzEndpoint::class;

    /**
     * @var string
     */
    protected $method = 'GET';
    /**
     * @var string
     */
    protected $url = 'https://online.gls-czech.com/tt_page.php?tt_value=$1';
}
