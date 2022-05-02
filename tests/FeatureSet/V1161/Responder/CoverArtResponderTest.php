<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CoverArtResponderTest extends MockeryTestCase
{
    private string $covertArt = 'some-covert-art';

    private string $contentType = 'some-content-type';

    private CoverArtResponder $subject;

    public function setUp(): void
    {
        $this->subject = new CoverArtResponder(
            $this->covertArt,
            $this->contentType,
        );
    }

    public function testIsBinaryResponderReturnsTrue(): void
    {
        $this->assertTrue(
            $this->subject->isBinaryResponder()
        );
    }

    public function testWriteResponseWrites(): void
    {
        $response = Mockery::mock(ResponseInterface::class);
        $stream = Mockery::mock(StreamInterface::class);

        $response->shouldReceive('getBody')
            ->withNoArgs()
            ->once()
            ->andReturn($stream);
        $response->shouldReceive('withHeader')
            ->with('Content-Type', $this->contentType)
            ->once()
            ->andReturnSelf();

        $stream->shouldReceive('write')
            ->with($this->covertArt)
            ->once();

        $this->assertSame(
            $response,
            $this->subject->writeResponse($response)
        );
    }
}
