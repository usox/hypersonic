<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use ArrayIterator;
use DateTime;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Traversable;
use Usox\HyperSonic\Exception\ErrorCodeEnum;
use Usox\HyperSonic\Exception\MethodCallFailedException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\AlbumList2DataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

class GetAlbumList2MethodTest extends TestCase
{
    private ResponderFactoryInterface&MockInterface $responderFactory;

    private GetAlbumList2Method $subject;

    protected function setUp(): void
    {
        $this->responderFactory = Mockery::mock(ResponderFactoryInterface::class);

        $this->subject = new GetAlbumList2Method(
            $this->responderFactory,
        );
    }

    public function testInvokeErrorsIfTypeIsNotAllowed(): void
    {
        $dataProvider = Mockery::mock(AlbumList2DataProviderInterface::class);

        $this->expectException(MethodCallFailedException::class);
        $this->expectExceptionCode(ErrorCodeEnum::MISSING_PARAMETER->value);

        call_user_func($this->subject, $dataProvider, [], []);
    }

    public function testInvokeErrorsIfTypeIsByYearAndFromYearIsMissing(): void
    {
        $dataProvider = Mockery::mock(AlbumList2DataProviderInterface::class);

        $this->expectException(MethodCallFailedException::class);
        $this->expectExceptionCode(ErrorCodeEnum::MISSING_PARAMETER->value);

        call_user_func($this->subject, $dataProvider, ['type' => 'byYear'], []);
    }

    public function testInvokeErrorsIfTypeIsByYearAndToYearIsMissing(): void
    {
        $dataProvider = Mockery::mock(AlbumList2DataProviderInterface::class);

        $this->expectException(MethodCallFailedException::class);
        $this->expectExceptionCode(ErrorCodeEnum::MISSING_PARAMETER->value);

        call_user_func($this->subject, $dataProvider, ['type' => 'byYear', 'fromYear' => 666], []);
    }

    public function testInvokeErrorsIfTypeIsByGenreAndGenreIsMissing(): void
    {
        $dataProvider = Mockery::mock(AlbumList2DataProviderInterface::class);

        $this->expectException(MethodCallFailedException::class);
        $this->expectExceptionCode(ErrorCodeEnum::MISSING_PARAMETER->value);

        call_user_func($this->subject, $dataProvider, ['type' => 'byGenre'], []);
    }

    public function testInvokeReturnsDataForByYear(): void
    {
        $dataProvider = Mockery::mock(AlbumList2DataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $fromYear = 42;
        $toYear = 666;

        $dataProvider->shouldReceive('getAlbums')
            ->with(
                'byYear',
                10,
                0,
                ['year' => ['from' => $fromYear, 'to' => $toYear]],
                null
            )
            ->once()
            ->andReturn(new ArrayIterator());

        $this->responderFactory->shouldReceive('createAlbumList2Responder')
            ->with(Mockery::type(Traversable::class))
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func(
                $this->subject,
                $dataProvider,
                ['type' => 'byYear', 'fromYear' => (string) $fromYear, 'toYear' => (string) $toYear],
                []
            )
        );
    }

    public function testInvokeReturnsDataForByGenre(): void
    {
        $dataProvider = Mockery::mock(AlbumList2DataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $genre = 'snafu';

        $dataProvider->shouldReceive('getAlbums')
            ->with(
                'byGenre',
                10,
                0,
                ['genre' => 'snafu'],
                null
            )
            ->once()
            ->andReturn(new ArrayIterator());

        $this->responderFactory->shouldReceive('createAlbumList2Responder')
            ->with(Mockery::type(Traversable::class))
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func(
                $this->subject,
                $dataProvider,
                ['type' => 'byGenre', 'genre' => $genre],
                []
            )
        );
    }

    public function orderTypeDataProvider(): array
    {
        return [
            ['random'],
            ['newest'],
            ['frequent'],
            ['recent'],
            ['starred'],
            ['alphabeticalByName'],
            ['alphabeticalByArtist'],
        ];
    }

    /**
     * @dataProvider orderTypeDataProvider
     */
    public function testInvokeTransformsAndReturnsData(
        string $type
    ): void {
        $dataProvider = Mockery::mock(AlbumList2DataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $albumId = 'some-album-id';
        $name = 'some-album-name';
        $coverArtId = 'some-art-id';
        $songCount = 666;
        $createDate = new DateTime();
        $duration = 42;
        $artistName = 'some-artist-name';
        $artistId = 'some-artist-id';
        $musicFolderId = 'some-music-folder-id';
        $offset = 33;

        $dataProvider->shouldReceive('getAlbums')
            ->with(
                $type,
                500,
                $offset,
                [],
                $musicFolderId
            )
            ->once()
            ->andReturn(new ArrayIterator([[
                'id' => $albumId,
                'name' => $name,
                'coverArtId' => $coverArtId,
                'songCount' => $songCount,
                'createDate' => $createDate,
                'duration' => $duration,
                'artistName' => $artistName,
                'artistId' => $artistId,
            ]]));

        $this->responderFactory->shouldReceive('createAlbumList2Responder')
            ->with(Mockery::on(
                static fn (Traversable $albums): bool => iterator_to_array($albums) === [[
                    'id' => $albumId,
                    'name' => $name,
                    'coverArt' => $coverArtId,
                    'songCount' => $songCount,
                    'created' => $createDate->format(DATE_ATOM),
                    'duration' => $duration,
                    'artist' => $artistName,
                    'artistId' => $artistId,
                ]]
            ))
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func(
                $this->subject,
                $dataProvider,
                ['type' => $type, 'musicFolderId' => $musicFolderId, 'size' => 12345, 'offset' => $offset],
                []
            )
        );
    }
}
