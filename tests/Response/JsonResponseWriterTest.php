<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Usox\HyperSonic\Exception\ErrorCodeEnum;

class JsonResponseWriterTest extends MockeryTestCase
{
    private string $apiVersion = '6.6.6';

    private JsonResponseWriter $subject;

    public function setUp(): void
    {
        $this->subject = new JsonResponseWriter(
            $this->apiVersion,
        );
    }

    public function testWriteWritesData(): void
    {
        $response = Mockery::mock(ResponseInterface::class);
        $responder = Mockery::mock(FormattedResponderInterface::class);
        $stream = Mockery::mock(StreamInterface::class);

        $responder->shouldReceive('writeJson')
            ->with([
                'status' => 'ok',
                'version' => $this->apiVersion,
            ])
            ->once();

        $response->shouldReceive('getBody')
            ->withNoArgs()
            ->once()
            ->andReturn($stream);
        $response->shouldReceive('withHeader')
            ->with('Content-Type', 'application/json')
            ->once()
            ->andReturnSelf();

        $stream->shouldReceive('write')
            ->with(
                (string) json_encode([
                    'subsonic-response' => [
                        'status' => 'ok',
                        'version' => $this->apiVersion,
                    ],
                ], JSON_PRETTY_PRINT)
            )
            ->once();

        $this->assertSame(
            $response,
            $this->subject->write($response, $responder)
        );
    }

    public function testWriteErrorWrites(): void
    {
        $response = Mockery::mock(ResponseInterface::class);
        $stream = Mockery::mock(StreamInterface::class);

        $message = 'some-message';
        $errorCode = ErrorCodeEnum::MISSING_PARAMETER;

        $response->shouldReceive('getBody')
            ->withNoArgs()
            ->once()
            ->andReturn($stream);
        $response->shouldReceive('withHeader')
            ->with('Content-Type', 'application/json')
            ->once()
            ->andReturnSelf();

        $stream->shouldReceive('write')
            ->with(
                (string) json_encode([
                    'subsonic-response' => [
                        'status' => 'failed',
                        'version' => $this->apiVersion,
                        'error' => [
                            'code' => $errorCode,
                            'message' => $message,
                        ],
                    ],
                ], JSON_PRETTY_PRINT)
            )
            ->once();

        $this->assertSame(
            $response,
            $this->subject->writeError($response, $errorCode, $message)
        );
    }
}
