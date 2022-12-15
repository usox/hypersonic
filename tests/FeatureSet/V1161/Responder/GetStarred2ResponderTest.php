<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use ArrayIterator;
use Mockery;

class GetStarred2ResponderTest extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    private array $album = ['some-album'];

    private array $song = ['some-song'];

    private GetStarred2Responder $subject;

    protected function setUp(): void
    {
        $this->subject = new GetStarred2Responder(
            new ArrayIterator([$this->song]),
            new ArrayIterator([$this->album])
        );
    }

    public function testWriteXmlWrites(): void
    {
        $xmlArray = Mockery::mock(XMLArray::class);

        $xmlArray->shouldReceive('add')
            ->with('album', null, $this->album)
            ->once();
        $xmlArray->shouldReceive('add')
            ->with('song', null, $this->song)
            ->once();

        $xmlArray->shouldReceive('startLoop')
            ->with(
                'starred2',
                [],
                Mockery::on(static function ($cb) use ($xmlArray): bool {
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
            [
                'album' => [$this->album],
                'song' => [$this->song],
            ],
            $data['starred2']
        );
    }

    public function testIsBinaryResponderReturnsFalse(): void
    {
        $this->assertFalse(
            $this->subject->isBinaryResponder()
        );
    }
}
