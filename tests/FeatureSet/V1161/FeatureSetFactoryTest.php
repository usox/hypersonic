<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

use Mockery\Adapter\Phpunit\MockeryTestCase;

class FeatureSetFactoryTest extends MockeryTestCase
{
    private FeatureSetFactory $subject;

    public function setUp(): void
    {
        $this->subject = new FeatureSetFactory();
    }

    public function testGetVersionReturnsValue(): void
    {
        $this->assertSame(
            '1.16.1',
            $this->subject->getVersion()
        );
    }

    public function methodDataProvider(): array
    {
        return [
            ['ping.view', Method\PingMethod::class],
            ['getLicense.view', Method\GetLicenseMethod::class],
            ['getArtists.view', Method\GetArtistsMethod::class],
            ['getCoverArt.view', Method\GetCoverArtMethod::class],
        ];
    }

    /**
     * @dataProvider methodDataProvider
     */
    public function testMethodCreation(
        string $apiMethod,
        string $className
    ): void {
        $this->assertInstanceOf(
            $className,
            $this->subject->getMethods()[$apiMethod]()
        );
    }
}