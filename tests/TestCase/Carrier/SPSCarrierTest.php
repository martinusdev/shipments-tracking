<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Carrier;

use MartinusDev\ShipmentsTracking\Carriers\Carrier;
use MartinusDev\ShipmentsTracking\Carriers\SPSCarrier;
use PHPUnit\Framework\TestCase;

class SPSCarrierTest extends TestCase
{
    public function testGetTrackingUrl()
    {
        $carrier = Carrier::load(SPSCarrier::NAME);
        $this->assertSame('https://t-t.sps-sro.sk/result.php/?cmd=SDG_SEARCH&sprache=SK&sdg_landnr=703&sdg_mandnr=030&sdg_lfdnr=28751101', $carrier->getTrackingUrl('703-030-28751101'));
    }

    public function testGetTrackingUrlWithoutDashes()
    {
        $carrier = Carrier::load(SPSCarrier::NAME);
        $this->assertSame('https://t-t.sps-sro.sk/result.php/?cmd=SDG_SEARCH&sprache=SK&sdg_landnr=703&sdg_mandnr=030&sdg_lfdnr=28751101', $carrier->getTrackingUrl('7030302875110112'));
    }

    public function testGetTrackingUrlWithChecksum()
    {
        $carrier = Carrier::load(SPSCarrier::NAME);
        $this->assertSame('https://t-t.sps-sro.sk/result.php/?cmd=SDG_SEARCH&sprache=SK&sdg_landnr=703&sdg_mandnr=030&sdg_lfdnr=28751101', $carrier->getTrackingUrl('703-030-2875110112'));
    }
}
