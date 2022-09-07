<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Endpoints\SPS;

class ParcelStatus
{
    /**
     * @var string $ParcelNumber
     */
    protected $ParcelNumber = null;

    /**
     * @var string $Date
     */
    protected $Date = null;

    /**
     * @var string $Time
     */
    protected $Time = null;

    /**
     * @var string $Status
     */
    protected $Status = null;

    /**
     * @var int $StatusCodeX
     */
    protected $StatusCodeX = null;

    /**
     * @var string $Center
     */
    protected $Center = null;

    /**
     * @var string $Remark
     */
    protected $Remark = null;

    /**
     * @var float $GeoLocX
     */
    protected $GeoLocX = null;

    /**
     * @var float $GeoLocY
     */
    protected $GeoLocY = null;

    /**
     * @param string $ParcelNumber
     * @param string $Date
     * @param string $Time
     * @param string $Status
     * @param int $StatusCodeX
     * @param string $Center
     * @param string $Remark
     * @param float $GeoLocX
     * @param float $GeoLocY
     */
    public function __construct($ParcelNumber, $Date, $Time, $Status, $StatusCodeX, $Center, $Remark, $GeoLocX, $GeoLocY)
    {
        $this->ParcelNumber = $ParcelNumber;
        $this->Date = $Date;
        $this->Time = $Time;
        $this->Status = $Status;
        $this->StatusCodeX = $StatusCodeX;
        $this->Center = $Center;
        $this->Remark = $Remark;
        $this->GeoLocX = $GeoLocX;
        $this->GeoLocY = $GeoLocY;
    }

    /**
     * @return string
     */
    public function getParcelNumber()
    {
        return $this->ParcelNumber;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->Date;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->Time;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * @return int
     */
    public function getStatusCodeX()
    {
        return $this->StatusCodeX;
    }

    /**
     * @return string
     */
    public function getCenter()
    {
        return $this->Center;
    }

    /**
     * @return string
     */
    public function getRemark()
    {
        return $this->Remark;
    }

    /**
     * @return float
     */
    public function getGeoLocX()
    {
        return $this->GeoLocX;
    }

    /**
     * @return float
     */
    public function getGeoLocY()
    {
        return $this->GeoLocY;
    }
}
