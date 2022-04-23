<?php

declare(strict_types=1);

namespace Usox\HyperSonic;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Usox\HyperSonic\Authentication\AuthenticationManagerInterface;
use Usox\HyperSonic\Authentication\Exception\AuthenticationFailedException;
use Usox\HyperSonic\Exception\ErrorCodeEnum;
use Usox\HyperSonic\FeatureSet\V1161\FeatureSetFactoryInterface;
use Usox\HyperSonic\Response\ResponseWriterFactoryInterface;
use Usox\HyperSonic\Response\ResponseWriterInterface;

class HyperSonicTest extends MockeryTestCase
{
    private MockInterface $featureSetFactory;

    private array $dataProvider;

    private MockInterface $responseWriterFactory;

    private MockInterface $authenticationManager;

    private HyperSonic $subject;

    public function setUp(): void
    {
        $this->featureSetFactory = Mockery::mock(FeatureSetFactoryInterface::class);
        $this->dataProvider = [];
        $this->responseWriterFactory = Mockery::mock(ResponseWriterFactoryInterface::class);
        $this->authenticationManager = Mockery::mock(AuthenticationManagerInterface::class);

        $this->subject = new HyperSonic(
            $this->featureSetFactory,
            $this->dataProvider,
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
                $errorMessage
            )
            ->once()
            ->andReturn($response);

        $this->assertSame(
            $response,
            call_user_func($this->subject, $request, $response, [])
        );
    }
}
