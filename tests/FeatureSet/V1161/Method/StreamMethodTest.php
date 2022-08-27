<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Usox\HyperSonic\Exception\ErrorCodeEnum;
use Usox\HyperSonic\Exception\MethodCallFailedException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\StreamDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\BinaryResponderInterface;

class StreamMethodTest extends MockeryTestCase
{
    private ResponderFactoryInterface&MockInterface $responderFactory;

    private StreamMethod $subject;

    protected function setUp(): void
    {
        $this->responderFactory = Mockery::mock(ResponderFactoryInterface::class);

        $this->subject = new StreamMethod(
            $this->responderFactory,
        );
    }

    public function testInvokeReturnsErrorsIfNotFound(): void
    {
        $dataProvider = Mockery::mock(StreamDataProviderInterface::class);

        $this->expectException(MethodCallFailedException::class);
        $this->expectExceptionCode(ErrorCodeEnum::NOT_FOUND->value);

        $dataProvider->shouldReceive('stream')
            ->with('', null, null)
            ->once()
            ->andReturnNull();

        call_user_func($this->subject, $dataProvider, [], []);
    }

    public function testInvokeReturnsResponder(): void
    {
        $dataProvider = Mockery::mock(StreamDataProviderInterface::class);
        $responder = Mockery::mock(BinaryResponderInterface::class);

        $songId = '666';
        $bitrate = 192;
        $format = 'wma';
        $length = 42;
        $streamData = ['some' => 'data', 'length' => $length];

        $dataProvider->shouldReceive('stream')
            ->with($songId, $format, $bitrate)
            ->once()
            ->andReturn($streamData);

        $this->responderFactory->shouldReceive('createStreamResponder')
            ->with($streamData + ['estimatedContentLength' => $length * $bitrate * 1000 / 8])
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func(
                $this->subject,
                $dataProvider,
                [
                    'id' => (int) $songId,
                    'format' => $format,
                    'maxBitRate' => (string) $bitrate,
                    'estimateContentLength' => 'true',
                ],
                []
            )
        );
    }
}
