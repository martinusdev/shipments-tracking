<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints;

class GlsCzEndpoint extends Endpoint
{
    use GlsTrait;

    public $url = 'https://online.gls-czech.com/tt_page_xml.php?pclid=$1';
}
