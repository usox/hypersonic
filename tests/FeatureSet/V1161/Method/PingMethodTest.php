<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Usox\HyperSonic\Exception\MethodCallFailedException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\PingDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

class PingMethodTest extends MockeryTestCase
{
    private MockInterface $responderFactory;

    private PingMethod $subject;

    protected function setUp(): void
    {
        $this->responderFactory = Mockery::mock(ResponderFactoryInterface::class);

        $this->subject = new PingMethod(
            $this->responderFactory,
        );
    }

    public function testInvokeThrowsExceptionIfPingFails(): void
    {
        $this->expectException(MethodCallFailedException::class);

        $dataProvider = Mockery::mock(PingDataProviderInterface::class);

        $dataProvider->shouldReceive('isOk')
            ->withNoArgs()
            ->once()
            ->andReturnFalse();

        call_user_func($this->subject, $dataProvider, [], []);
    }

    public function testInvokeReturnsResponder(): void
    {
        $dataProvider = Mockery::mock(PingDataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $dataProvider->shouldReceive('isOk')
            ->withNoArgs()
            ->once()
            ->andReturnTrue();

        $this->responderFactory->shouldReceive('createPingResponder')
            ->withNoArgs()
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func($this->subject, $dataProvider, [], [])
        );
    }
}
