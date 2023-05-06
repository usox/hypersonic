<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use ArrayIterator;
use Mockery;

class GetRandomSongsResponderTest extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    private array $song = ['some-song'];

    private GetRandomSongsResponder $subject;

    protected function setUp(): void
    {
        $this->subject = new GetRandomSongsResponder(
            new ArrayIterator([$this->song]),
        );
    }

    public function testWriteXmlWrites(): void
    {
        $xmlArray = Mockery::mock(XMLArray::class);

        $xmlArray->shouldReceive('add')
            ->with('song', null, $this->song)
            ->once();

        $xmlArray->shouldReceive('startLoop')
            ->with(
                'randomSongs',
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
                'song' => [$this->song],
            ],
            $data['randomSongs']
        );
    }

    public function testIsBinaryResponderReturnsFalse(): void
    {
        $this->assertFalse(
            $this->subject->isBinaryResponder()
        );
    }
}
