<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\GetCoverArtDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

class GetCoverArtMethodTest extends MockeryTestCase
{
    private MockInterface $responderFactory;

    private GetCoverArtMethod $subject;

    protected function setUp(): void
    {
        $this->responderFactory = Mockery::mock(ResponderFactoryInterface::class);

        $this->subject = new GetCoverArtMethod(
            $this->responderFactory,
        );
    }

    public function testInvokeReturnsData(): void
    {
        $dataProvider = Mockery::mock(GetCoverArtDataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $id = 'some-id';
        $art = 'some-art';
        $contentType = 'content-type';

        $this->responderFactory->shouldReceive('createCoverArtResponder')
            ->with($art, $contentType)
            ->once()
            ->andReturn($responder);

        $dataProvider->shouldReceive('getArt')
            ->with($id)
            ->once()
            ->andReturn(['art' => $art, 'contentType' => $contentType]);

        $this->assertSame(
            $responder,
            call_user_func($this->subject, $dataProvider, ['id' => $id], [])
        );
    }
}
