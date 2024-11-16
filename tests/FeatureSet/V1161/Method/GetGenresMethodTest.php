<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use ArrayObject;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\GenreListDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

class GetGenresMethodTest extends MockeryTestCase
{
    private MockInterface $responder_factory;

    private GetGenresMethod $subject;

    protected function setUp(): void
    {
        $this->responder_factory = Mockery::mock(ResponderFactoryInterface::class);

        $this->subject = new GetGenresMethod(
            $this->responder_factory,
        );
    }

    public function testInvokeReturnsResponder(): void
    {
        $dataProvider = Mockery::mock(GenreListDataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $value = 666;

        $dataProvider->shouldReceive('getGenres')
            ->withNoArgs()
            ->once()
            ->andReturn(new ArrayObject([$value]));

        $this->responder_factory->shouldReceive('createGenresResponder')
            ->with([$value])
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func($this->subject, $dataProvider, [], []),
        );
    }
}
