<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\PPLEndpoint;

class PPLCarrier extends Carrier
{
    public const NAME = 'PPL';

    protected $endPointClass = PPLEndpoint::class;

}
