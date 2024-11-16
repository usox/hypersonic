<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StreamResponderTest extends MockeryTestCase
{
    private StreamInterface&MockInterface $stream;

    private int $estimatedContentLength = 666;

    private string $contenType = 'some/type';

    private StreamResponder $subject;

    protected function setUp(): void
    {
        $this->stream = Mockery::mock(StreamInterface::class);

        $this->subject = new StreamResponder([
            'contentType' => $this->contenType,
            'stream' => $this->stream,
            'estimatedContentLength' => $this->estimatedContentLength,
        ]);
    }

    public function testWriteResponseWrites(): void
    {
        $response = Mockery::mock(ResponseInterface::class);

        $response->shouldReceive('withHeader')
            ->with(
                'Content-Length',
                (string) $this->estimatedContentLength,
            )
            ->once()
            ->andReturnSelf();
        $response->shouldReceive('withBody')
            ->with($this->stream)
            ->once()
            ->andReturnSelf();
        $response->shouldReceive('withHeader')
            ->with('Content-Transfer-Encoding', 'binary')
            ->once()
            ->andReturnSelf();
        $response->shouldReceive('withHeader')
            ->with('Cache-Control', 'no-cache')
            ->once()
            ->andReturnSelf();
        $response->shouldReceive('withHeader')
            ->with('Content-Type', $this->contenType)
            ->once()
            ->andReturnSelf();

        $this->assertSame(
            $response,
            $this->subject->writeResponse($response),
        );
    }
}
