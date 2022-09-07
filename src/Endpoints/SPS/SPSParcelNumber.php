<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints\SPS;

use MartinusDev\ShipmentsTracking\Carriers\SPSCarrier;

class SPSParcelNumber
{
    /**
     * @var string
     */
    public $landNr;
    /**
     * @var string
     */
    public $manNr;
    /**
     * @var string
     */
    public $lfdNr;

    public function __construct(string $parcelNumber)
    {
        if (!preg_match(SPSCarrier::REGEX, $parcelNumber, $match)) {
            throw new \InvalidArgumentException('Not matched SPS number: ' . $parcelNumber);
        }
        $this->landNr = $match[1];
        $this->manNr = $match[2];
        $this->lfdNr = $match[3];
    }
}
