<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints;

class GlsSkEndpoint extends Endpoint
{
    use GlsTrait;

    public $url = 'https://online.gls-slovakia.sk/tt_page_xml.php?pclid=$1';
}
