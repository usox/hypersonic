<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class ArtistsResponderTest extends MockeryTestCase
{
    private array $artistList = [];

    private array $ignoredArticles = ['some-article'];

    private string $indexName = 'some-index-name';

    private array $artist = ['some-artist'];

    private ArtistsResponder $subject;

    public function setUp(): void
    {
        $this->artistList['ignoredArticles'] = $this->ignoredArticles;
        $this->artistList['index'] = [[
            'name' => $this->indexName,
            'artist' => [$this->artist],
        ]];

        $this->subject = new ArtistsResponder(
            $this->artistList,
        );
    }

    public function testWriteXmlWrites(): void
    {
        $xmlArray = Mockery::mock(XMLArray::class);

        $xmlArray->shouldReceive('add')
            ->with('artist', null, $this->artist)
            ->once();

        $xmlArray->shouldReceive('startLoop')
            ->with(
                'index',
                ['name' => $this->indexName],
                Mockery::on(function ($cb) use ($xmlArray) {
                    $cb($xmlArray);

                    return true;
                })
            )
            ->once();

        $xmlArray->shouldReceive('startLoop')
            ->with(
                'artists',
                ['ignoredArticles' => $this->ignoredArticles],
                Mockery::on(function ($cb) use ($xmlArray) {
                    $cb($xmlArray);

                    return true;
                })
            )
            ->once();

        $this->subject->writeXml($xmlArray);
    }

    public function testWriteJsonWrites(): void
    {
        $data = [];

        $this->subject->writeJson($data);

        $this->assertSame(
            $this->artistList,
            $data['artists']
        );
    }

    public function testIsBinaryResponderReturnsFalse(): void
    {
        $this->assertFalse(
            $this->subject->isBinaryResponder()
        );
    }
}
