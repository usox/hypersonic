<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Mockery\Adapter\Phpunit\MockeryTestCase;

class ResponderFactoryTest extends MockeryTestCase
{
    private ResponderFactory $subject;

    public function setUp(): void
    {
        $this->subject = new ResponderFactory();
    }

    public function responderDataProvider(): array
    {
        return [
            ['createArtistsResponder', ArtistsResponder::class, [[]]],
            ['createLicenseResponder', LicenseResponder::class, [[]]],
            ['createPingResponder', PingResponder::class, []],
            ['createCoverArtResponder', CoverArtResponder::class, ['cover-art', 'content-type']],
        ];
    }

    /**
     * @dataProvider responderDataProvider
     */
    public function testFactoryMethods(
        string $methodName,
        string $expectedInstance,
        array $parameter
    ): void {
        $this->assertInstanceOf(
            $expectedInstance,
            call_user_func_array([$this->subject, $methodName], $parameter)
        );
    }
}
