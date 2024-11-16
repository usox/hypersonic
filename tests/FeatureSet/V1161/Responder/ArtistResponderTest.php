<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class ArtistResponderTest extends MockeryTestCase
{
    private array $artist = ['some-artist'];

    private array $albums = [['some-albums']];

    private ArtistResponder $subject;

    protected function setUp(): void
    {
        $this->subject = new ArtistResponder(
            $this->artist,
            $this->albums,
        );
    }

    public function testWriteXmlWrites(): void
    {
        $xmlArray = Mockery::mock(XMLArray::class);

        $xmlArray->shouldReceive('start')
            ->with('artist', $this->artist)
            ->once()
            ->andReturnSelf();
        $xmlArray->shouldReceive('add')
            ->with('album', null, ['some-albums'])
            ->once()
            ->andReturnSelf();
        $xmlArray->shouldReceive('end')
            ->withNoArgs()
            ->once();

        $this->subject->writeXml($xmlArray);
    }

    public function testWriteJsonWrites(): void
    {
        $data = [];

        $this->subject->writeJson($data);

        $this->assertSame(
            [...$this->artist, 'album' => $this->albums],
            $data['artist'],
        );
    }

    public function testIsBinaryResponderReturnsFalse(): void
    {
        $this->assertFalse(
            $this->subject->isBinaryResponder(),
        );
    }
}
