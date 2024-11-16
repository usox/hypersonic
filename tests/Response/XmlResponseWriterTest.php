<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use AaronDDM\XMLBuilder\XMLArray;
use AaronDDM\XMLBuilder\XMLBuilder;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Usox\HyperSonic\Exception\ErrorCodeEnum;

class XmlResponseWriterTest extends MockeryTestCase
{
    private MockInterface $xmlBuilder;

    private string $apiVersion = '6.6.6';

    private XmlResponseWriter $subject;

    protected function setUp(): void
    {
        $this->xmlBuilder = Mockery::mock(XMLBuilder::class);

        $this->subject = new XmlResponseWriter(
            $this->xmlBuilder,
            $this->apiVersion,
        );
    }

    public function testWriteWritesXmlData(): void
    {
        $response = Mockery::mock(ResponseInterface::class);
        $responder = Mockery::mock(FormattedResponderInterface::class);
        $xmlArray = Mockery::mock(XMLArray::class);
        $stream = Mockery::mock(StreamInterface::class);

        $result = 'some-result';

        $this->xmlBuilder->shouldReceive('createXMLArray')
            ->withNoArgs()
            ->once()
            ->andReturn($xmlArray);
        $this->xmlBuilder->shouldReceive('getXML')
            ->withNoArgs()
            ->once()
            ->andReturn($result);

        $response->shouldReceive('getBody')
            ->withNoArgs()
            ->once()
            ->andReturn($stream);
        $response->shouldReceive('withHeader')
            ->with('Content-Type', 'application/xml')
            ->once()
            ->andReturnSelf();

        $stream->shouldReceive('write')
            ->with($result)
            ->once();

        $xmlArray->shouldReceive('start')
            ->with(
                'subsonic-response',
                [
                    'xmlns' => 'http://subsonic.org/restapi',
                    'status' => 'ok',
                    'version' => $this->apiVersion,
                ],
            )
            ->once()
            ->andReturnSelf();
        $xmlArray->shouldReceive('end')
            ->withNoArgs()
            ->once();

        $responder->shouldReceive('writeXml')
            ->with($xmlArray)
            ->once();

        $this->assertSame(
            $response,
            $this->subject->write($response, $responder),
        );
    }

    public function testWriteWritesError(): void
    {
        $response = Mockery::mock(ResponseInterface::class);
        $xmlArray = Mockery::mock(XMLArray::class);
        $stream = Mockery::mock(StreamInterface::class);

        $errorCode = ErrorCodeEnum::MISSING_PARAMETER;
        $message = 'some-message';
        $result = 'some-result';

        $this->xmlBuilder->shouldReceive('createXMLArray')
            ->withNoArgs()
            ->once()
            ->andReturn($xmlArray);
        $this->xmlBuilder->shouldReceive('getXML')
            ->withNoArgs()
            ->once()
            ->andReturn($result);

        $response->shouldReceive('getBody')
            ->withNoArgs()
            ->once()
            ->andReturn($stream);
        $response->shouldReceive('withHeader')
            ->with('Content-Type', 'application/xml')
            ->once()
            ->andReturnSelf();

        $stream->shouldReceive('write')
            ->with($result)
            ->once();

        $xmlArray->shouldReceive('start')
            ->with(
                'subsonic-response',
                [
                    'xmlns' => 'http://subsonic.org/restapi',
                    'status' => 'failed',
                    'version' => $this->apiVersion,
                ],
            )
            ->once()
            ->andReturnSelf();
        $xmlArray->shouldReceive('add')
            ->with(
                'error',
                null,
                ['code' => $errorCode->value, 'message' => $message],
            )
            ->once()
            ->andReturnSelf();
        $xmlArray->shouldReceive('end')
            ->withNoArgs()
            ->once();

        $this->assertSame(
            $response,
            $this->subject->writeError($response, $errorCode, $message),
        );
    }
}
