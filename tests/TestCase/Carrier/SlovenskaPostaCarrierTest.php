<?php

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Carrier;

use MartinusDev\ShipmentsTracking\Carriers\Carrier;
use MartinusDev\ShipmentsTracking\Carriers\SlovenskaPostaCarrier;
use MartinusDev\ShipmentsTracking\ShipmentsTracking;
use MartinusDev\ShipmentsTracking\Test\TestSuite\TestHttpClient;
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
        $regex = SlovenskaPostaCarrier::REGEX;
        $this->assertSame($result, preg_match($regex, $number), 'Regex test failed for ' . $number);
    }

    public function regexDataProvider()
    {
        return [
            ['ZB079400152SK', 1],
            ['RR079400152SK', 1],
            ['ZB079400152CZ', 0], //cz na konci
            ['01079400152SK', 0], //bez zaciatocnych pismen
        ];
    }

    public function testGetTrackingUrl()
    {
        $carrier = Carrier::load(SlovenskaPostaCarrier::NAME);
        $this->assertSame('https://tandt.posta.sk/zasielky/ZB079400152SK', $carrier->getTrackingUrl('ZB079400152SK'));
    }

    public function testGet()
    {
        $testClient = new TestHttpClient(file_get_contents(COMPARISIONS . '/Endpoints/SlovenskaPosta/response_1.json'), 'https://api.posta.sk/tracking?q=ZB079400232SK');
        new ShipmentsTracking(['client' => $testClient]);
        $carrier = Carrier::load(SlovenskaPostaCarrier::NAME);
        $shipment = $carrier->getShipment('ZB079400232SK');
        $this->assertSame('{"number":"ZB079400232SK","carrierName":"SlovenskaPosta","trackingLink":"https:\/\/tandt.posta.sk\/zasielky\/ZB079400232SK","states":[{"name":"received","date":{"date":"2019-01-15 09:27:22.000000","timezone_type":3,"timezone":"UTC"},"description":"Podan\u00e1 na po\u0161te OU \u017dilina"},{"name":"notified","date":{"date":"2019-01-15 09:31:15.000000","timezone_type":3,"timezone":"UTC"},"description":"Z\u00e1sielka ulo\u017een\u00e1 na po\u0161te {post}"},{"name":"delivered","date":{"date":"2019-01-15 09:31:12.000000","timezone_type":3,"timezone":"UTC"},"description":"Z\u00e1sielka ulo\u017een\u00e1 na po\u0161te {post}"}]}', json_encode($shipment));
    }
}
