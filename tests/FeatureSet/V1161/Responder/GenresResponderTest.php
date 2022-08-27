<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class GenresResponderTest extends MockeryTestCase
{
    private string $title = 'some-title & co';

    private int $albumCount = 666;

    private int $songCount = 42;

    private array $genres;

    private GenresResponder $subject;

    protected function setUp(): void
    {
        $this->genres = [[
            'value' => $this->title,
            'albumCount' => $this->albumCount,
            'songCount' => $this->songCount,
        ]];

        $this->subject = new GenresResponder(
            $this->genres,
        );
    }

    public function testWriteXmlWrites(): void
    {
        $XMLArray = Mockery::mock(XMLArray::class);

        $XMLArray->shouldReceive('startLoop')
            ->with(
                'genres',
                [],
                Mockery::on(static function (callable $loop) use ($XMLArray): bool {
                    $loop($XMLArray);
                    return true;
                })
            );
        $XMLArray->shouldReceive('add')
            ->with(
                'genre',
                htmlspecialchars($this->title),
                [
                    'albumCount' => $this->albumCount,
                    'songCount' => $this->songCount,
                ]
            )
            ->once();

        $this->subject->writeXml($XMLArray);
    }

    public function testWriteJsonWritesData(): void
    {
        $data = [];

        $this->subject->writeJson($data);

        $this->assertSame(
            ['genres' => ['genre' => $this->genres]],
            $data
        );
    }

    public function testIsBinaryResponderReturnsFalse(): void
    {
        $this->assertFalse(
            $this->subject->isBinaryResponder()
        );
    }
}
