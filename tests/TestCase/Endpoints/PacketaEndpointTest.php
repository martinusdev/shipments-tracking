<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints;

use MartinusDev\ShipmentsTracking\Carriers\PacketaCarrier;
use MartinusDev\ShipmentsTracking\Endpoints\Packeta\StatusRecord;
use MartinusDev\ShipmentsTracking\Endpoints\Packeta\StatusRecords;
use MartinusDev\ShipmentsTracking\Endpoints\PacketaEndpoint;
use MartinusDev\ShipmentsTracking\Shipment\Shipment;
use MartinusDev\ShipmentsTracking\ShipmentsTracking;
use MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints\Packeta\Lib\PacketaSoapRequestAPITest;
use MartinusDev\ShipmentsTracking\Test\TestSuite\TestHttpClient;
use PHPUnit\Framework\TestCase;

class PacketaEndpointTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|SoapClient
     */
    private $soapClientMock;

    protected function setUp()
    {
        $this->soapClientMock = (new PacketaSoapRequestAPITest())->getMockFromWsdlSpsLocal();

        $testClient = new TestHttpClient();
        new ShipmentsTracking(['client' => $testClient]);

        parent::setUp();
    }

    public function testGetStates(): void
    {
        $packetaCarrier = new PacketaCarrier();
        $endpoint = new PacketaEndpoint();

        $this->soapClientMock
            ->method('packetTracking')
            ->with('API_PASSWORD', 'Z4964561515')
            ->willReturn($this->correctResponse());

        $endpoint->setClient($this->soapClientMock);
        $shipment = new Shipment([
            'carrierName' => PacketaCarrier::NAME,
            'number' => 'Z4964561515',
            'carrier' => $packetaCarrier,
        ], ['loadStates' => false]);

        $states = $endpoint->getStates($shipment);
        $this->assertCount(2, $states);
    }

    private function correctResponse(): StatusRecords
    {
        $status1 = new StatusRecord();
        $status1->statusCode = 1;
        $status2 = new StatusRecord();
        $status2->statusCode = 2;
        $statusRecords = new StatusRecords();
        $statusRecords->record = [$status1, $status2];

        return $statusRecords;
    }
}
