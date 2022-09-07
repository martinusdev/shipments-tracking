<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints\SPS;

use MartinusDev\ShipmentsTracking\Endpoints\SPS\ParcelStatus;
use PHPUnit\Framework\TestCase;

class ParcelStatusTest extends TestCase
{
    public function testBasic()
    {
        $status = new ParcelStatus('1234', '2022-08-12', '14:34:33', 'Delivered', 100, '1', 'remark', 102.3, 9.9);
        $this->assertSame('1234', $status->getParcelNumber());
        $this->assertSame('Delivered', $status->getStatus());
        $this->assertSame('1', $status->getCenter());
        $this->assertSame('remark', $status->getRemark());
        $this->assertEquals(102.3, $status->getGeoLocX());
        $this->assertEquals(9.9, $status->getGeoLocY());
    }
}
