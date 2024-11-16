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

    public static function responderDataProvider(): \Iterator
    {
        yield ['createAlbumResponder', AlbumResponder::class, [['album'], ['songs']]];
        yield ['createArtistsResponder', ArtistsResponder::class, [[]]];
        yield ['createLicenseResponder', LicenseResponder::class, [[]]];
        yield ['createPingResponder', PingResponder::class, []];
        yield ['createCoverArtResponder', CoverArtResponder::class, ['cover-art', 'content-type']];
        yield ['createArtistResponder', ArtistResponder::class, [['artist'], ['albums']]];
        yield ['createGenresResponder', GenresResponder::class, [['genres']]];
        yield ['createMusicFoldersResponder', MusicFoldersResponder::class, [new ArrayIterator(['folders'])]];
        yield ['createStreamResponder', StreamResponder::class, [['data']]];
        yield ['createAlbumList2Responder', AlbumList2Responder::class, [new ArrayIterator()]];
        yield ['createStarred2Responder', GetStarred2Responder::class, [new ArrayIterator(), new ArrayIterator(),]];
        yield ['createRandomSongsResponder', GetRandomSongsResponder::class, [new ArrayIterator()]];
    }

    #[DataProvider('responderDataProvider')]
    public function testFactoryMethods(
        string $methodName,
        string $expectedInstance,
        array $parameter,
    ): void {
        $this->assertInstanceOf(
            $expectedInstance,
            call_user_func_array([$this->subject, $methodName], $parameter),
        );
    }
}
