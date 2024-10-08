<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints;

use MartinusDev\ShipmentsTracking\Carriers\SPSCarrier;
use MartinusDev\ShipmentsTracking\Endpoints\SPS\ParcelStatus;
use MartinusDev\ShipmentsTracking\Endpoints\SPSEndpoint;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\Shipment\ShipmentStates\State;
use MartinusDev\ShipmentsTracking\ShipmentsTracking;
use MartinusDev\ShipmentsTracking\Test\TestSuite\TestHttpClient;
use Nimda\Expedition\Test\TestCase\Deliverer\SPS\Lib\SoapRequestAPITest;
use PHPUnit\Framework\TestCase;

class SPSEndpointTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|SoapClient
     */
    private $soapClientMock;

    protected function setUp()
    {
        $this->soapClientMock = (new SoapRequestAPITest())->getMockFromWsdlSpsLocal();
        $testClient = new TestHttpClient();
        new ShipmentsTracking(['client' => $testClient]);

        parent::setUp();
    }

    public function testGetStates(): void
    {
        $spsCarrier = new SPSCarrier();
        $endpoint = new SPSEndpoint();

        $this->soapClientMock
            ->method('getParcelStatus')
            ->with(703, 30, 28751102, 'E')
            ->willReturn($this->correctResponse());

        $endpoint->setClient($this->soapClientMock);
        $shipment = new Shipment([
            'carrierName' => SPSCarrier::NAME,
            'number' => '703-030-28751102',
            'carrier' => $spsCarrier,
            // 'trackingLink' => $this->getTrackingUrl($number),
        ]);

        $states = $endpoint->getStates($shipment);
        $this->assertCount(2, $states);
    }

    public function testParseResponse(): void
    {
        $this->markTestSkipped('todo');
        $response = file_get_contents(COMPARISIONS . '/Endpoints/SPS/SPS.xml');
        $endpoint = new SPSEndpoint();

        $parsed = $endpoint->parseResponse($response);

        $this->assertCount(5, $parsed);
        /** @var State $deliveredState */
        $deliveredState = $parsed[0];
        $this->assertInstanceOf(State::class, $deliveredState);
        $this->assertSame('delivered', (string)$deliveredState);
        $this->assertSame('-', $deliveredState->description);
    }

    /**
     * @param array $event
     * @param string $stateName
     * @dataProvider parseEvent()
     */
    public function testParseEvent(array $event, string $stateName): void
    {
        $endpoint = new SPSEndpoint();
        $event += [
            'statusCode' => 0,
        ];
        $status = new ParcelStatus(0, '', '', '', $event['statusCode'], '', '', '', '');
        $state = $endpoint->parseEvent($status);
        $this->assertSame($stateName, (string)$state);
    }

    public function parseEvent(): array
    {
        return [
            [
                [
                    'statusCode' => 10,
                ],
                'received',
            ],
            [
                [
                    'statusCode' => 99,
                ],
                'unknown',
            ],
        ];
    }

    /**
     * @return ParcelStatus[]
     */
    private function correctResponse(): array
    {
        $status1 = new ParcelStatus(0, '', '', 'Inbound', 10, '', '', '', '');
        $status2 = new ParcelStatus(0, '', '', 'Registration', 44, '', '', '', '');

        return [
            $status1,
            $status2,
        ];
    }
}
