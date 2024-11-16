<?php

declare(strict_types=1);

namespace Usox\HyperSonic;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Usox\HyperSonic\Authentication\AuthenticationManagerInterface;
use Usox\HyperSonic\Authentication\AuthenticationProviderInterface;
use Usox\HyperSonic\Authentication\Exception\AuthenticationFailedException;
use Usox\HyperSonic\Exception\ErrorCodeEnum;
use Usox\HyperSonic\FeatureSet\V1161\Contract\PingDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\FeatureSetFactoryInterface;
use Usox\HyperSonic\Response\BinaryResponderInterface;
use Usox\HyperSonic\Response\ResponseWriterFactoryInterface;
use Usox\HyperSonic\Response\ResponseWriterInterface;

class HyperSonicTest extends MockeryTestCase
{
    /**
     * @var mixed[]
     */
    public array $dataProvider = [];

    private MockInterface $featureSetFactory;

    private MockInterface $responseWriterFactory;

    private MockInterface $authenticationManager;

    private HyperSonic $subject;

    protected function setUp(): void
    {
        $this->featureSetFactory = Mockery::mock(FeatureSetFactoryInterface::class);
        $this->dataProvider = [];
        $this->responseWriterFactory = Mockery::mock(ResponseWriterFactoryInterface::class);
        $this->authenticationManager = Mockery::mock(AuthenticationManagerInterface::class);

        $this->subject = new HyperSonic(
            $this->featureSetFactory,
            [],
            $this->responseWriterFactory,
            $this->authenticationManager,
        );
    }

    public function testInvokeErrorsOnAuthError(): void
    {
        $request = Mockery::mock(ServerRequestInterface::class);
        $response = Mockery::mock(ResponseInterface::class);
        $responseWriter = Mockery::mock(ResponseWriterInterface::class);

        $errorMessage = 'some-error';
        $apiVersion = 'some-version';

        $this->featureSetFactory->shouldReceive('getVersion')
            ->withNoArgs()
            ->once()
            ->andReturn($apiVersion);

        $request->shouldReceive('getQueryParams')
            ->withNoArgs()
            ->once()
            ->andReturn([]);

        $this->responseWriterFactory->shouldReceive('createXmlResponseWriter')
            ->with($apiVersion)
            ->once()
            ->andReturn($responseWriter);

        $this->authenticationManager->shouldReceive('auth')
            ->with($request)
            ->once()
            ->andThrow(new AuthenticationFailedException($errorMessage));

        $responseWriter->shouldReceive('writeError')
            ->with(
                $response,
                ErrorCodeEnum::WRONG_USERNAME_OR_PASSWORD,
                $errorMessage,
            )
            ->once()
            ->andReturn($response);

        $this->assertSame(
            $response,
            call_user_func($this->subject, $request, $response, []),
        );
    }

    public function testRunErrorIfMethodNotAvailable(): void
    {
        $hypersonic = new HyperSonic(
            $this->featureSetFactory,
            [],
            $this->responseWriterFactory,
            $this->authenticationManager,
        );

        $request = Mockery::mock(ServerRequestInterface::class);
        $response = Mockery::mock(ResponseInterface::class);
        $responseWriter = Mockery::mock(ResponseWriterInterface::class);

        $apiVersion = 'some-version';

        $this->featureSetFactory->shouldReceive('getVersion')
            ->withNoArgs()
            ->once()
            ->andReturn($apiVersion);
        $this->featureSetFactory->shouldReceive('getMethods')
            ->withNoArgs()
            ->once()
            ->andReturn([]);

        $request->shouldReceive('getQueryParams')
            ->withNoArgs()
            ->once()
            ->andReturn([]);

        $this->responseWriterFactory->shouldReceive('createXmlResponseWriter')
            ->with($apiVersion)
            ->once()
            ->andReturn($responseWriter);

        $this->authenticationManager->shouldReceive('auth')
            ->with($request)
            ->once();

        $responseWriter->shouldReceive('writeError')
            ->with(
                $response,
                ErrorCodeEnum::SERVER_VERSION_INCOMPATIBLE,
            )
            ->once()
            ->andReturn($response);

        $this->assertSame(
            $response,
            $hypersonic->run($request, $response, ['methodName' => 'snafu']),
        );
    }

    public function testRunReturnsBinaryResponse(): void
    {
        $request = Mockery::mock(ServerRequestInterface::class);
        $response = Mockery::mock(ResponseInterface::class);
        $responseWriter = Mockery::mock(ResponseWriterInterface::class);
        $dataProvider = Mockery::mock(PingDataProviderInterface::class);
        $responder = Mockery::mock(BinaryResponderInterface::class);

        $apiVersion = 'some-version';
        $methodName = 'some-method-name';

        $hypersonic = new HyperSonic(
            $this->featureSetFactory,
            [
                $methodName => static fn () => $dataProvider,
            ],
            $this->responseWriterFactory,
            $this->authenticationManager,
        );

        $method = static fn () => $responder;

        $responder->shouldReceive('isBinaryResponder')
            ->withNoArgs()
            ->once()
            ->andReturnTrue();
        $responder->shouldReceive('writeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $this->featureSetFactory->shouldReceive('getVersion')
            ->withNoArgs()
            ->once()
            ->andReturn($apiVersion);
        $this->featureSetFactory->shouldReceive('getMethods')
            ->withNoArgs()
            ->once()
            ->andReturn([
                $methodName => static fn (): \Closure => $method,
            ]);

        $request->shouldReceive('getQueryParams')
            ->withNoArgs()
            ->once()
            ->andReturn(['f' => 'json']);

        $this->responseWriterFactory->shouldReceive('createJsonResponseWriter')
            ->with($apiVersion)
            ->once()
            ->andReturn($responseWriter);

        $this->authenticationManager->shouldReceive('auth')
            ->with($request)
            ->once();

        $this->assertSame(
            $response,
            $hypersonic->run($request, $response, ['methodName' => $methodName]),
        );
    }

    public function testInitReturnsNewInstance(): void
    {
        $this->assertInstanceOf(
            HyperSonic::class,
            HyperSonic::init(
                Mockery::mock(FeatureSetFactoryInterface::class),
                Mockery::mock(AuthenticationProviderInterface::class),
                [],
            ),
        );
    }
}
