<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

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
        static::assertSame(
            '1.16.1',
            $this->subject->getVersion()
        );
    }

    public static function methodDataProvider(): array
    {
        return [
            ['ping.view', Method\PingMethod::class],
            ['getAlbum.view', Method\GetAlbumMethod::class],
            ['getAlbumList2.view', Method\GetAlbumList2Method::class],
            ['getLicense.view', Method\GetLicenseMethod::class],
            ['getArtists.view', Method\GetArtistsMethod::class],
            ['getCoverArt.view', Method\GetCoverArtMethod::class],
            ['getArtist.view', Method\GetArtistMethod::class],
            ['getGenres.view', Method\GetGenresMethod::class],
            ['getMusicFolders.view', Method\GetMusicFoldersMethod::class],
            ['stream.view', Method\StreamMethod::class],
            ['getStarred2.view', Method\GetStarred2Method::class],
            ['getRandomSongs.view', Method\GetRandomSongsMethod::class],
        ];
    }

    /**
     * @dataProvider methodDataProvider
     */
    public function testMethodCreation(
        string $apiMethod,
        string $className
    ): void {
        static::assertInstanceOf(
            $className,
            $this->subject->getMethods()[$apiMethod]()
        );
    }
}
