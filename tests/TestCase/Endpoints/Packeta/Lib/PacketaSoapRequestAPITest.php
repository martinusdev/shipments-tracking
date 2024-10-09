<?php

declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints\Packeta\Lib;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PacketaSoapRequestAPITest extends TestCase
{
    public function testCreateShipment(): void
    {
        $this->markTestIncomplete(''); // resolve warning that there are no tests in the class
    }

    /**
     * @return MockObject|SoapClient
     */
    public function getMockFromWsdlSpsLocal()
    {
        /** @var SoapClient&MockObject $soapClientMock */
        $soapClientMock = $this->getMockFromWsdl(
            TESTSUITE . '/wsdl/soap_php_bugfix_zasilkovna.wsdl',
            '',
            '',
            [],
            true,
            [
                'cache_wsdl' => WSDL_CACHE_BOTH,
            ]
        );

        return $soapClientMock;
    }
}
