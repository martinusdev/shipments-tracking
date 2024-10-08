<?php

declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints\SPS\Lib;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SoapRequestAPITest extends TestCase
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
            TESTSUITE . '/wsdl/service_soap_sps.xml',
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
