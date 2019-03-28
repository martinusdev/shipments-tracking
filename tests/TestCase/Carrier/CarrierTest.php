<?php
/**
 * Created by PhpStorm.
 * User: samue
 * Date: 26.03.2019
 * Time: 16:17
 */

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Carrier;

use MartinusDev\ShipmentsTracking\Carriers\Carrier;
use MartinusDev\ShipmentsTracking\Carriers\SlovenskaPostaCarrier;
use PHPUnit\Framework\TestCase;

class CarrierTest extends TestCase
{

    public function testLoadExists()
    {
        $name = 'SlovenskaPosta';
        $carrier = Carrier::load($name);
        $this->assertInstanceOf(SlovenskaPostaCarrier::class, $carrier);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Carrier DummyCarrierName does not exists
     */
    public function testLoadNotExists()
    {
        $name = 'DummyCarrierName';
        Carrier::load($name);
    }

    /**
     */
    public function testAllCarriers()
    {
        $carriers = Carrier::CARRIERS;
        foreach ($carriers as $name) {
            $carrier = Carrier::load($name);
            $this->assertInstanceOf(Carrier::class, $carrier);
        }
    }

}
