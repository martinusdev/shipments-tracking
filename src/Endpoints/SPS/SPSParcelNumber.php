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
    /**
     * @var int
     */
    public $version;

    public function __construct(string $parcelNumber)
    {
        // v1 tracking number format
        if (preg_match(SPSCarrier::REGEX, $parcelNumber, $match) === 1) {
            $this->landNr = $match[1];
            $this->manNr = $match[2];
            $this->lfdNr = $match[3];
            $this->version = 1;

            return;
        }

        // v2 tracking number format (SK<alphanum_znaky>)
        if (strtolower(substr($parcelNumber,0, 2)) === 'sk') {
            $this->lfdNr = $parcelNumber;
            $this->version = 2;

            return;
        }

        throw new \InvalidArgumentException('Not matched SPS number: ' . $parcelNumber);
    }
}
