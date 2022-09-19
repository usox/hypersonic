<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use ArrayIterator;
use DateTime;
use Generator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Traversable;
use Usox\HyperSonic\FeatureSet\V1161\Contract\GetStarred2DataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

class GetStarred2MethodTest extends MockeryTestCase
{
    private ResponderFactoryInterface&MockInterface $responderFactory;

    private GetStarred2Method $subject;

    protected function setUp(): void
    {
        $this->responderFactory = Mockery::mock(ResponderFactoryInterface::class);

        $this->subject = new GetStarred2Method(
            $this->responderFactory,
        );
    }

    public function testInvokeReturnsEmptyResultForEmptyMusicFolder(): void
    {
        $dataProvider = Mockery::mock(GetStarred2DataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $dataProvider->shouldReceive('getStarred')
            ->with(null)
            ->once()
            ->andReturn(['songs' => new ArrayIterator(), 'albums' => new ArrayIterator()]);

        $this->responderFactory->shouldReceive('createStarred2Responder')
            ->with(
                Mockery::type(Generator::class),
                Mockery::type(Generator::class)
            )
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func(
                $this->subject,
                $dataProvider,
                [],
                []
            )
        );
    }

    public function testInvokeReturnsData(): void
    {
        $dataProvider = Mockery::mock(GetStarred2DataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $musicFolderId = 'some-folder';
        $albumId = 666;
        $albumName = 'some-album-name';
        $artistName = 'some-artist-name';
        $artistId = 42;
        $songCount = 21;
        $coverArtId = 'some-cover-art';
        $length = 1488;
        $createDate = new DateTime();
        $starredDate = new DateTime();
        $albumYear = 2022;

        $songId = 123;
        $songName = 'some-song-name';
        $size = 555666;

        $dataProvider->shouldReceive('getStarred')
            ->with($musicFolderId)
            ->once()
            ->andReturn(
                [
                    'songs' => new ArrayIterator(
                        [[
                            'id' => $songId,
                            'name' => $songName,
                            'albumName' => $albumName,
                            'artistName' => $artistName,
                            'coverArtId' => $coverArtId,
                            'albumId' => $albumId,
                            'artistId' => $artistId,
                            'length' => $length,
                            'createDate' => $createDate,
                            'starredDate' => $starredDate,
                            'filesize' => $size,
                        ]]
                    ),
                    'albums' => new ArrayIterator(
                        [[
                            'id' => $albumId,
                            'name' => $albumName,
                            'artistName' => $artistName,
                            'artistId' => $artistId,
                            'songCount' => $songCount,
                            'coverArtId' => $coverArtId,
                            'length' => $length,
                            'createDate' => $createDate,
                            'starredDate' => $starredDate,
                            'year' => $albumYear,
                        ]]
                    ),
                ]
            );

        $this->responderFactory->shouldReceive('createStarred2Responder')
            ->with(
                Mockery::on(
                    function (Traversable $data): bool {
                        $this->assertNotEmpty(
                            iterator_to_array($data)
                        );
                        return true;
                    },
                ),
                Mockery::on(
                    function (Traversable $data): bool {
                        $this->assertNotEmpty(
                            iterator_to_array($data)
                        );
                        return true;
                    },
                ),
            )
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func(
                $this->subject,
                $dataProvider,
                ['musicFolderId' => $musicFolderId],
                []
            )
        );
    }
}
