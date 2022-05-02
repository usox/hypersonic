<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class LicenseResponderTest extends MockeryTestCase
{
    private LicenseResponder $subject;

    private array $licenseData = ['some-data'];

    public function setUp(): void
    {
        $this->subject = new LicenseResponder(
            $this->licenseData
        );
    }

    public function testWriteXmlWrites(): void
    {
        $xmlArray = Mockery::mock(XMLArray::class);

        $xmlArray->shouldReceive('add')
            ->with('license', null, $this->licenseData)
            ->once();

        $this->subject->writeXml($xmlArray);
    }

    public function testWriteJsonWrites(): void
    {
        $data = [];

        $this->subject->writeJson($data);

        $this->assertSame(
            $this->licenseData,
            $data['license']
        );
    }

    public function testIsBinaryResponderReturnsFalse(): void
    {
        $this->assertFalse(
            $this->subject->isBinaryResponder()
        );
    }
}
