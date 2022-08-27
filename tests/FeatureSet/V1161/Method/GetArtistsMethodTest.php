<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use DateTime;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\ArtistListDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

class GetArtistsMethodTest extends MockeryTestCase
{
    private MockInterface $responderFactory;

    private GetArtistsMethod $subject;

    protected function setUp(): void
    {
        $this->responderFactory = Mockery::mock(ResponderFactoryInterface::class);

        $this->subject = new GetArtistsMethod(
            $this->responderFactory,
        );
    }

    public function testInvokeReturnsData(): void
    {
        $dataProvider = Mockery::mock(ArtistListDataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $musicFolderId = 'some-music-folder';
        $ignoredArticles = ['some', 'articles'];
        $id = 'some-id';
        $name = 'some-name';
        $name2 = 'other-name';
        $coverArtId = 'some-cover-art-id';
        $artistImageUrl = 'some-artist-image-url';
        $albumCount = 666;
        $starred = new DateTime();

        $dataProvider->shouldReceive('getIgnoredArticles')
            ->withNoArgs()
            ->once()
            ->andReturn($ignoredArticles);
        $dataProvider->shouldReceive('getArtists')
            ->with($musicFolderId)
            ->once()
            ->andReturn([
                [
                    'id' => $id,
                    'name' => $name,
                    'coverArtId' => $coverArtId,
                    'artistImageUrl' => $artistImageUrl,
                    'albumCount' => $albumCount,
                    'starred' => $starred,
                ],
                [
                    'id' => $id,
                    'name' => $name2,
                    'coverArtId' => $coverArtId,
                    'artistImageUrl' => $artistImageUrl,
                    'albumCount' => $albumCount,
                    'starred' => $starred,
                ],
            ]);

        $this->responderFactory->shouldReceive('createArtistsResponder')
            ->with([
                'ignoredArticles' => 'some articles',
                'index' => [
                    [
                        'name' => 'S',
                        'artist' => [
                            [
                                'id' => $id,
                                'name' => $name,
                                'coverArt' => $coverArtId,
                                'artistImageUrl' => $artistImageUrl,
                                'albumCount' => $albumCount,
                                'starred' => $starred->format(DATE_ATOM),
                            ],
                        ],
                    ],
                    [
                        'name' => 'O',
                        'artist' => [
                            [
                                'id' => $id,
                                'name' => $name2,
                                'coverArt' => $coverArtId,
                                'artistImageUrl' => $artistImageUrl,
                                'albumCount' => $albumCount,
                                'starred' => $starred->format(DATE_ATOM),
                            ],
                        ],
                    ],
                ],
            ])
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func($this->subject, $dataProvider, ['musicFolderId' => $musicFolderId], [])
        );
    }
}
