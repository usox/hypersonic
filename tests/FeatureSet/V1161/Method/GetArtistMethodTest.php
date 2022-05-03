<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use DateTime;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Usox\HyperSonic\Exception\MethodCallFailedException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\ArtistDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

class GetArtistMethodTest extends MockeryTestCase
{
    private MockInterface $responderFactory;

    private GetArtistMethod $subject;

    public function setUp(): void
    {
        $this->responderFactory = Mockery::mock(ResponderFactoryInterface::class);

        $this->subject = new GetArtistMethod(
            $this->responderFactory,
        );
    }

    public function testInvokeThrowsExceptionIfArtistWasNotFound(): void
    {
        $this->expectException(MethodCallFailedException::class);

        $dataProvider = Mockery::mock(ArtistDataProviderInterface::class);

        $dataProvider->shouldReceive('getArtist')
            ->with('')
            ->once()
            ->andReturnNull();

        call_user_func($this->subject, $dataProvider, [], []);
    }

    public function testInvokeReturnsData(): void
    {
        $responder = Mockery::mock(ResponderInterface::class);
        $dataProvider = Mockery::mock(ArtistDataProviderInterface::class);

        $artistId = 666;
        $artistName = 'some-name';
        $covertArtId = 'some-id';
        $artistImageUrl = 'some-image-url';
        $albumCount = 555;
        $albumId = 42;
        $albumName = 'some-album';
        $songCount = 2322;
        $duration = 1111;
        $playCount = 22434;
        $created = new DateTime();
        $year = 11231;
        $genre = 'Metal';

        $dataProvider->shouldReceive('getArtist')
            ->with('')
            ->once()
            ->andReturn([
                'id' => $artistId,
                'name' => $artistName,
                'coverArtId' => $covertArtId,
                'artistImageUrl' => $artistImageUrl,
                'albumCount' => $albumCount,
                'albums' => [[
                    'id' => $albumId,
                    'name' => $albumName,
                    'artistId' => $artistId,
                    'artistName' => $artistName,
                    'coverArtId' => $covertArtId,
                    'songCount' => $songCount,
                    'duration' => $duration,
                    'playCount' => $playCount,
                    'createDate' => $created,
                    'year' => $year,
                    'genre' => $genre,
                ]],
            ]);

        $this->responderFactory->shouldReceive('createArtistResponder')
            ->with(
                [
                    'id' => (string) $artistId,
                    'name' => $artistName,
                    'coverArt' => $covertArtId,
                    'artistImageUrl' => $artistImageUrl,
                    'albumCount' => $albumCount,
                ],
                [[
                    'id' => (string) $albumId,
                    'name' => $albumName,
                    'artist' => $artistName,
                    'artistId' => (string) $artistId,
                    'coverArt' => $covertArtId,
                    'songCount' => $songCount,
                    'duration' => $duration,
                    'playCount' => $playCount,
                    'created' => $created->format(DATE_ATOM),
                    'year' => $year,
                    'genre' => $genre,
                ]]
            )
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func($this->subject, $dataProvider, [], [])
        );
    }
}
