<?php

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\SlovenskaPostaEndpoint;

class SlovenskaPostaCarrier extends Carrier
{
    const NAME = 'SlovenskaPosta';

    protected $endPointClass = SlovenskaPostaEndpoint::class;

    const REGEX = '/^([A-Z]{2}[0-9]{9}SK)$/i';
    /** @var string  */
    protected $method = 'GET';
    /** @var string  */
    protected $url = 'https://tandt.posta.sk/zasielky/$1';
}
