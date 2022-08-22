<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class AlbumResponderTest extends MockeryTestCase
{
    private array $album = ['some-album'];

    private array $songs = [['some-songs']];

    private AlbumResponder $subject;

    public function setUp(): void
    {
        $this->subject = new AlbumResponder(
            $this->album,
            $this->songs,
        );
    }

    public function testWriteXmlWrites(): void
    {
        $xmlArray = Mockery::mock(XMLArray::class);

        $xmlArray->shouldReceive('start')
            ->with('album', $this->album)
            ->once()
            ->andReturnSelf();
        $xmlArray->shouldReceive('add')
            ->with('song', null, ['some-songs'])
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
            array_merge($this->album, ['song' => $this->songs]),
            $data['album']
        );
    }

    public function testIsBinaryResponderReturnsFalse(): void
    {
        $this->assertFalse(
            $this->subject->isBinaryResponder()
        );
    }
}
