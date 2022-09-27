<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Carrier;

use MartinusDev\ShipmentsTracking\Carriers\Carrier;
use MartinusDev\ShipmentsTracking\Carriers\PacketaCarrier;
use PHPUnit\Framework\TestCase;

class PacketaCarrierTest extends TestCase
{
    public function testGetTrackingUrl()
    {
        $carrier = Carrier::load(PacketaCarrier::NAME);
        $this->assertSame('https://tracking.packeta.com/en/?id=Z4964561515', $carrier->getTrackingUrl('Z4964561515'));
    }
}
