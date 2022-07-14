<?php

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\GlsCzEndpoint;

class GlsCzCarrier extends Carrier
{
    public const NAME = 'GlsCz';

    public const REGEX = '/^(90[0-9]{9})$/i';

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
