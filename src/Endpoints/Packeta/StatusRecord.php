<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints\Packeta;

class StatusRecord
{
    public $dateTime;
    public $statusCode;
    public $codeText;
    public $statusText;
    public $branchId;
    public $destinationBranchId;
    public $externalTrackingCode;
}
