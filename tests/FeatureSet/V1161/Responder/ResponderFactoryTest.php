<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use ArrayIterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResponderFactoryTest extends TestCase
{
    private ResponderFactory $subject;

    protected function setUp(): void
    {
        $this->subject = new ResponderFactory();
    }

    public static function responderDataProvider(): array
    {
        return [
            ['createAlbumResponder', AlbumResponder::class, [['album'], ['songs']]],
            ['createArtistsResponder', ArtistsResponder::class, [[]]],
            ['createLicenseResponder', LicenseResponder::class, [[]]],
            ['createPingResponder', PingResponder::class, []],
            ['createCoverArtResponder', CoverArtResponder::class, ['cover-art', 'content-type']],
            ['createArtistResponder', ArtistResponder::class, [['artist'], ['albums']]],
            ['createGenresResponder', GenresResponder::class, [['genres']]],
            ['createMusicFoldersResponder', MusicFoldersResponder::class, [new ArrayIterator(['folders'])]],
            ['createStreamResponder', StreamResponder::class, [['data']]],
            ['createAlbumList2Responder', AlbumList2Responder::class, [new ArrayIterator()]],
            ['createStarred2Responder', GetStarred2Responder::class, [new ArrayIterator(), new ArrayIterator(),]],
            ['createRandomSongsResponder', GetRandomSongsResponder::class, [new ArrayIterator()]],
        ];
    }

    #[DataProvider('responderDataProvider')]
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
