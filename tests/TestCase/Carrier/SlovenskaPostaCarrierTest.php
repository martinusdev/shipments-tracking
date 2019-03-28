<?php

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Carrier;

use MartinusDev\ShipmentsTracking\Carriers\Carrier;
use MartinusDev\ShipmentsTracking\Carriers\SlovenskaPostaCarrier;
use PHPUnit\Framework\TestCase;

class SlovenskaPostaCarrierTest extends TestCase
{
    /**
     * @param $number
     * @param $result
     *
     * @dataProvider regexDataProvider()
     */
    public function testRegex($number, $result)
    {
        //$carrier = Carrier::load(SlovenskaPostaCarrier::NAME);
        $regex = SlovenskaPostaCarrier::REGEX;
        $this->assertSame($result, preg_match($regex, $number), 'Regex test failed for ' . $number);
        //$this->assertSame($result, preg_match($regex, $number), 'Regex test failed for ' . $number);
    }

    public function regexDataProvider()
    {
        return [
            ['ZB079400152SK', 1],
            ['RR079400152SK', 1],
            ['ZB079400152CZ', 0],
            ['01079400152SK', 0],
        ];
    }

    public function testGetTrackingUrl()
    {
        $carrier = Carrier::load(SlovenskaPostaCarrier::NAME);
        $this->assertSame('https://tandt.posta.sk/zasielky/ZB079400152SK', $carrier->getTrackingUrl('ZB079400152SK'));
    }
}
