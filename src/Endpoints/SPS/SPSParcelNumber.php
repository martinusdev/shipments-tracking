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
        // v2 tracking number format (SK<alphanum_znaky>)
        if (preg_match(SPSCarrier::REGEX, $parcelNumber) === 1) {
            $this->landNr = '';
            $this->manNr = '';
            $this->lfdNr = $parcelNumber;
            $this->version = 2;

            return;
        }

        // v1 tracking number format
        if (preg_match(SPSCarrier::REGEX_V1, $parcelNumber, $match) === 1) {
            $this->landNr = $match[1];
            $this->manNr = $match[2];
            $this->lfdNr = $match[3];
            $this->version = 1;

            return;
        }

        throw new \InvalidArgumentException('Not matched SPS number: ' . $parcelNumber);
    }
}
