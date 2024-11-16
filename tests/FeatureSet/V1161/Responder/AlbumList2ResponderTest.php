<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use ArrayIterator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class AlbumList2ResponderTest extends MockeryTestCase
{
    private array $album = ['some-album'];

    private AlbumList2Responder $subject;

    protected function setUp(): void
    {
        $this->subject = new AlbumList2Responder(
            new ArrayIterator([$this->album]),
        );
    }

    public function testWriteXmlWrites(): void
    {
        $xmlArray = Mockery::mock(XMLArray::class);

        $xmlArray->shouldReceive('add')
            ->with('album', null, $this->album)
            ->once();

        $xmlArray->shouldReceive('startLoop')
            ->with(
                'albumList2',
                [],
                Mockery::on(static function ($cb) use ($xmlArray): bool {
                    $cb($xmlArray);
                    return true;
                }),
            )
            ->once();

        $this->subject->writeXml($xmlArray);
    }

    public function testWriteJsonWrites(): void
    {
        $data = [];

        $this->subject->writeJson($data);

        $this->assertSame(
            ['album' => [$this->album]],
            $data['albumList2'],
        );
    }

    public function testIsBinaryResponderReturnsFalse(): void
    {
        $this->assertFalse(
            $this->subject->isBinaryResponder(),
        );
    }
}
