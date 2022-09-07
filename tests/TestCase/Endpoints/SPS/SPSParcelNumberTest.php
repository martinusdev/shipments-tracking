<?php

namespace MartinusDev\ShipmentsTracking\Test\TestCase\Endpoints\SPS;

use MartinusDev\ShipmentsTracking\Endpoints\SPS\SPSParcelNumber;
use PHPUnit\Framework\TestCase;

class SPSParcelNumberTest extends TestCase
{
    /**
     * @param string $in
     * @return void
     * @dataProvider ValidNumbersProvider
     */
    public function testParse(string $in)
    {
        $SPSParcelNumber = new SPSParcelNumber($in);
        $this->assertSame('703', $SPSParcelNumber->landNr);
        $this->assertSame('010', $SPSParcelNumber->manNr);
        $this->assertSame('28751101', $SPSParcelNumber->lfdNr);
    }


    public function ValidNumbersProvider()
    {
        return [
            'classic' => ['703-010-28751101'],
            'with-checksum' => ['703-010-2875110112'],
            'api-number' => ['7030102875110112'],
            'short-api-number' => ['70301028751101'],
        ];
    }
}