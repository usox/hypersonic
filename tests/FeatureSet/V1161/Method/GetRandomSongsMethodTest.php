<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use ArrayIterator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Traversable;
use Usox\HyperSonic\FeatureSet\V1161\Contract\GetRandomSongsDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

class GetRandomSongsMethodTest extends TestCase
{
    private ResponderFactoryInterface&MockObject $responderFactory;

    private GetRandomSongsMethod $subject;

    private GetRandomSongsDataProviderInterface&MockObject $dataProvider;

    protected function setUp(): void
    {
        $this->responderFactory = $this->createMock(ResponderFactoryInterface::class);
        $this->dataProvider = $this->createMock(GetRandomSongsDataProviderInterface::class);

        $this->subject = new GetRandomSongsMethod(
            $this->responderFactory,
        );
    }

    public function testInvokeReturnsDataWithDefaultParams(): void
    {
        $songId = 666;
        $albumId = 42;
        $title = 'some-title';
        $album = 'some-album';
        $artist = 'some-artist';
        $trackNumber = 21;
        $year = 2003;
        $genre = 'Melodic Deathgrind';
        $coverArt = 'some-cover';
        $duration = 12345;
        $size = 434444;

        $result = $this->createMock(ResponderInterface::class);

        $this->dataProvider->expects($this->once())
            ->method('getRandomSongs')
            ->with(null, 10, null, null, null)
            ->willReturn(new ArrayIterator([[
                'id' => $songId,
                'albumId' => $albumId,
                'name' => $title,
                'albumName' => $album,
                'artistName' => $artist,
                'trackNumber' => $trackNumber,
                'year' => $year,
                'genre' => $genre,
                'coverArtId' => $coverArt,
                'length' => $duration,
                'filesize' => $size,
            ]]));

        $this->responderFactory->expects($this->once())
            ->method('createRandomSongsResponder')
            ->with(self::callback(static fn (Traversable $data): bool => iterator_to_array($data) === [[
                'id' => (string) $songId,
                'parent' => (string) $albumId,
                'title' => $title,
                'isDir' => 'false',
                'album' => $album,
                'artist' => $artist,
                'track' => $trackNumber,
                'year' => $year,
                'genre' => $genre,
                'coverArt' => $coverArt,
                'duration' => $duration,
                'size' => $size,
            ]]))
            ->willReturn($result);

        $this->assertSame($result, call_user_func($this->subject, $this->dataProvider, [], []));
    }

    public function testInvokeReturnsDataWithCustomParams(): void
    {
        $songId = 666;
        $albumId = 42;
        $title = 'some-title';
        $album = 'some-album';
        $artist = 'some-artist';
        $trackNumber = 21;
        $year = 2003;
        $genre = 'Melodic Deathgrind';
        $coverArt = 'some-cover';
        $duration = 12345;
        $size = 434444;
        $musicFolderId = 2222;
        $genreFromRequest = 'HARDCOOOORE!';
        $fromYear = 1234;
        $toYear = 5678;

        $result = $this->createMock(ResponderInterface::class);

        $this->dataProvider->expects($this->once())
            ->method('getRandomSongs')
            ->with((string) $musicFolderId, 500, $genreFromRequest, $fromYear, $toYear)
            ->willReturn(new ArrayIterator([[
                'id' => $songId,
                'albumId' => $albumId,
                'name' => $title,
                'albumName' => $album,
                'artistName' => $artist,
                'trackNumber' => $trackNumber,
                'year' => $year,
                'genre' => $genre,
                'coverArtId' => $coverArt,
                'length' => $duration,
                'filesize' => $size,
            ]]));

        $this->responderFactory->expects($this->once())
            ->method('createRandomSongsResponder')
            ->with(self::callback(static fn (Traversable $data): bool => iterator_to_array($data) === [[
                    'id' => (string) $songId,
                    'parent' => (string) $albumId,
                    'title' => $title,
                    'isDir' => 'false',
                    'album' => $album,
                    'artist' => $artist,
                    'track' => $trackNumber,
                    'year' => $year,
                    'genre' => $genre,
                    'coverArt' => $coverArt,
                    'duration' => $duration,
                    'size' => $size,
                ]]))
            ->willReturn($result);

        $this->assertSame($result, call_user_func(
            $this->subject,
            $this->dataProvider,
            [
                'musicFolderId' => $musicFolderId,
                'size' => '55555',
                'genre' => $genreFromRequest,
                'fromYear' => (string) $fromYear,
                'toYear' => (string) $toYear,
            ],
            [],
        ));
    }
}
