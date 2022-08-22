<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Cassandra\Date;
use DateTime;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Usox\HyperSonic\Exception\ErrorCodeEnum;
use Usox\HyperSonic\Exception\MethodCallFailedException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\AlbumDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

class GetAlbumMethodTest extends MockeryTestCase
{
    private ResponderFactoryInterface&MockInterface $responderFactory;

    private GetAlbumMethod $subject;

    public function setUp(): void
    {
        $this->responderFactory = Mockery::mock(ResponderFactoryInterface::class);

        $this->subject = new GetAlbumMethod(
            $this->responderFactory,
        );
    }

    public function testInvokeErrorsIfAlbumWasNotFound(): void
    {
        $dataProvider = Mockery::mock(AlbumDataProviderInterface::class);

        $this->expectException(MethodCallFailedException::class);
        $this->expectExceptionCode(ErrorCodeEnum::NOT_FOUND->value);

        $dataProvider->shouldReceive('getAlbum')
            ->with('')
            ->once()
            ->andReturnNull();

        call_user_func($this->subject, $dataProvider, [], []);
    }

    public function testInvokeReturnsResponder(): void
    {
        $dataProvider = Mockery::mock(AlbumDataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $albumId = 'some-album-id';
        $albumName = 'some-album-name';
        $covertArt = 'some-cover-art';
        $songCount = 666;
        $createDate = 12342345;
        $duration = 2134;
        $artistName = 'some-artist-name';
        $artistId = 'some-artist-id';
        $songId = 'some-song-id';
        $isDir = false;
        $songName = 'some-song-name';
        $trackNumber = 123;
        $songCoverArt = 'some-song-cover-art';
        $size = 444;
        $contentType = 'not/hing';
        $songDuration = 777;
        $songCreateDate = 2323422;
        $playCount = 11111;

        $dataProvider->shouldReceive('getAlbum')
            ->with($albumId)
            ->once()
            ->andReturn([
                'id' => $albumId,
                'name' => $albumName,
                'coverArtId' => $covertArt,
                'songCount' => $songCount,
                'createDate' => new DateTime('@'.$createDate),
                'duration' => $duration,
                'artistName' => $artistName,
                'artistId' => $artistId,
                'songs' => [
                    123 => [
                        'id' => $songId,
                        'isDir' => $isDir,
                        'name' => $songName,
                        'albumName' => $albumName,
                        'artistName' => $artistName,
                        'trackNumber' => $trackNumber,
                        'coverArtId' => $songCoverArt,
                        'size' => $size,
                        'contentType' => $contentType,
                        'duration' => $songDuration,
                        'createDate' => new DateTime('@'.$songCreateDate),
                        'albumId' => $albumId,
                        'artistId' => $artistId,
                        'playCount' => $playCount,
                    ],
                ],
            ]);

        $this->responderFactory->shouldReceive('createAlbumResponder')
            ->with(
                [
                    'id' => $albumId,
                    'name' => $albumName,
                    'coverArt' => $covertArt,
                    'songCount' => $songCount,
                    'created' => (new DateTime('@'.$createDate))->format(DATE_ATOM),
                    'duration' => $duration,
                    'artist' => $artistName,
                    'artistId' => $artistId,
                ],
                [[
                    'id' => $songId,
                    'isDir' => $isDir,
                    'title' => $songName,
                    'album' => $albumName,
                    'artist' => $artistName,
                    'track' => $trackNumber,
                    'coverArt' => $songCoverArt,
                    'size' => $size,
                    'contentType' => $contentType,
                    'duration' => $songDuration,
                    'created' => (new DateTime('@'.$songCreateDate))->format(DATE_ATOM),
                    'albumId' => $albumId,
                    'artistId' => $artistId,
                    'playCount' => $playCount,
                ]]
            )
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func($this->subject, $dataProvider, ['id' => $albumId], [])
        );
    }
}
