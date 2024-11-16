<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FeatureSetFactoryTest extends TestCase
{
    private FeatureSetFactory $subject;

    protected function setUp(): void
    {
        $this->subject = new FeatureSetFactory();
    }

    public function testGetVersionReturnsValue(): void
    {
        $this->assertSame('1.16.1', $this->subject->getVersion());
    }

    public static function methodDataProvider(): \Iterator
    {
        yield ['ping.view', Method\PingMethod::class];
        yield ['getAlbum.view', Method\GetAlbumMethod::class];
        yield ['getAlbumList2.view', Method\GetAlbumList2Method::class];
        yield ['getLicense.view', Method\GetLicenseMethod::class];
        yield ['getArtists.view', Method\GetArtistsMethod::class];
        yield ['getCoverArt.view', Method\GetCoverArtMethod::class];
        yield ['getArtist.view', Method\GetArtistMethod::class];
        yield ['getGenres.view', Method\GetGenresMethod::class];
        yield ['getMusicFolders.view', Method\GetMusicFoldersMethod::class];
        yield ['stream.view', Method\StreamMethod::class];
        yield ['getStarred2.view', Method\GetStarred2Method::class];
        yield ['getRandomSongs.view', Method\GetRandomSongsMethod::class];
    }

    #[DataProvider('methodDataProvider')]
    public function testMethodCreation(
        string $apiMethod,
        string $className,
    ): void {
        $this->assertInstanceOf($className, $this->subject->getMethods()[$apiMethod]());
    }
}
