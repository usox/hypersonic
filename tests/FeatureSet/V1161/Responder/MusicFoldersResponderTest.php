<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use ArrayIterator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Traversable;

class MusicFoldersResponderTest extends MockeryTestCase
{
    private string $name = 'some-name & co';

    private int $id = 666;

    private Traversable $musicFolders;

    private MusicFoldersResponder $subject;

    protected function setUp(): void
    {
        $this->musicFolders = new ArrayIterator([[
            'name' => $this->name,
            'id' => $this->id,
        ]]);

        $this->subject = new MusicFoldersResponder(
            $this->musicFolders,
        );
    }

    public function testWriteXmlWrites(): void
    {
        $XMLArray = Mockery::mock(XMLArray::class);

        $XMLArray->shouldReceive('startLoop')
            ->with(
                'musicFolders',
                [],
                Mockery::on(static function (callable $loop) use ($XMLArray): bool {
                    $loop($XMLArray);
                    return true;
                }),
            );
        $XMLArray->shouldReceive('add')
            ->with(
                'musicFolder',
                '',
                [
                    'name' => htmlspecialchars($this->name),
                    'id' => $this->id,
                ],
            )
            ->once();

        $this->subject->writeXml($XMLArray);
    }

    public function testWriteJsonWritesData(): void
    {
        $data = [];

        $this->subject->writeJson($data);

        $this->assertSame(
            ['musicFolders' => ['musicFolder' => iterator_to_array($this->musicFolders)]],
            $data,
        );
    }

    public function testIsBinaryResponderReturnsFalse(): void
    {
        $this->assertFalse(
            $this->subject->isBinaryResponder(),
        );
    }
}
