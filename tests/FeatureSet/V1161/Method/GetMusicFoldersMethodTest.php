<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use ArrayIterator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\MusicFolderListDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

class GetMusicFoldersMethodTest extends MockeryTestCase
{
    private MockInterface $responder_factory;

    private GetMusicFoldersMethod $subject;

    public function setUp(): void
    {
        $this->responder_factory = Mockery::mock(ResponderFactoryInterface::class);

        $this->subject = new GetMusicFoldersMethod(
            $this->responder_factory,
        );
    }

    public function testInvokeReturnsResponder(): void
    {
        $dataProvider = Mockery::mock(MusicFolderListDataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $value = new ArrayIterator([666]);

        $dataProvider->shouldReceive('getMusicFolders')
            ->withNoArgs()
            ->once()
            ->andReturn($value);

        $this->responder_factory->shouldReceive('createMusicFoldersResponder')
            ->with($value)
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func($this->subject, $dataProvider, [], [])
        );
    }
}
