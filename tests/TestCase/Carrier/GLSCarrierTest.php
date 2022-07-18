<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Carrier;

use MartinusDev\ShipmentsTracking\Carriers\Carrier;
use MartinusDev\ShipmentsTracking\Carriers\GlsSkCarrier;
use MartinusDev\ShipmentsTracking\ShipmentsTracking;
use MartinusDev\ShipmentsTracking\Test\TestSuite\TestHttpClient;
use PHPUnit\Framework\TestCase;

class GLSCarrierTest extends TestCase
{
    public function testGetTrackingUrl()
    {
        $carrier = Carrier::load(GlsSkCarrier::NAME);
        $this->assertSame('https://gls-group.eu/SK/sk/sledovanie-zasielok?match=809558682', $carrier->getTrackingUrl('809558682'));
    }

    public function testGet()
    {
        $testClient = new TestHttpClient(file_get_contents(COMPARISIONS . '/Endpoints/GLS/GLS_SK.xml'), 'https://online.gls-slovakia.sk/tt_page_xml.php?pclid=809558682');
        new ShipmentsTracking(['client' => $testClient]);
        $carrier = Carrier::load(GlsSkCarrier::NAME);
        $shipment = $carrier->getShipment('809558682');
        $this->assertSame('{"number":"809558682","carrierName":"GlsSk","trackingLink":"https:\/\/gls-group.eu\/SK\/sk\/sledovanie-zasielok?match=809558682","states":[{"name":"delivered","date":{"date":"2022-03-23 12:15:17.000000","timezone_type":3,"timezone":"UTC"},"description":"-"},{"name":"unknown","date":{"date":"2022-03-23 06:39:04.000000","timezone_type":3,"timezone":"UTC"},"description":"10:00-13:00"},{"name":"unknown","date":{"date":"2022-03-23 05:09:01.000000","timezone_type":3,"timezone":"UTC"},"description":""},{"name":"received","date":{"date":"2022-03-22 18:31:30.000000","timezone_type":3,"timezone":"UTC"},"description":"54"},{"name":"unknown","date":{"date":"2022-03-21 14:36:28.000000","timezone_type":3,"timezone":"UTC"},"description":"gls_martinus_gls_sk_2022-03-21_06-32-15.xml"}]}', json_encode($shipment));
    }
}
