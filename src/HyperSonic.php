<?php

declare(strict_types=1);

namespace Usox\HyperSonic;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Usox\HyperSonic\Authentication\AuthenticationManager;
use Usox\HyperSonic\Authentication\AuthenticationManagerInterface;
use Usox\HyperSonic\Authentication\AuthenticationProviderInterface;
use Usox\HyperSonic\Authentication\Exception\AbstractAuthenticationException;
use Usox\HyperSonic\Exception\ErrorCodeEnum;
use Usox\HyperSonic\FeatureSet\V1161\FeatureSetFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;
use Usox\HyperSonic\Response\ResponseWriterFactory;
use Usox\HyperSonic\Response\ResponseWriterFactoryInterface;

final class HyperSonic implements HyperSonicInterface
{
    /**
     * @param array<string, callable(): callable> $dataProvider
     */
    public function __construct(
        private readonly FeatureSetFactoryInterface $featureSetFactory,
        private readonly array $dataProvider,
        private readonly ResponseWriterFactoryInterface $responseWriterFactory,
        private readonly AuthenticationManagerInterface $authenticationManager,
    ) {
    }

    /**
     * @param array<string, scalar> $args
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        return $this->run($request, $response, $args);
    }

    /**
     * @param array<string, scalar> $args
     */
    public function run(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // subsonic response format
        $responseFormat = $request->getQueryParams()['f'] ?? 'xml';

        if ($responseFormat === 'xml') {
            $responseWriter = $this->responseWriterFactory->createXmlResponseWriter();
        } else {
            $responseWriter = $this->responseWriterFactory->createJsonResponseWriter();
        }

        try {
            $this->authenticationManager->auth($request);
        } catch (AbstractAuthenticationException $e) {
            return $responseWriter->writeError(
                $response,
                ErrorCodeEnum::WRONG_USERNAME_OR_PASSWORD,
                $e->getMessage()
            );
        }

        // ensure we filter all existing methods by the really implemented ones
        $methods = array_intersect_key(
            $this->featureSetFactory->getMethods(),
            $this->dataProvider
        );

        $methodName = $args['methodName'];

        /** @var callable|null $handler */
        $handler = $methods[$methodName] ?? null;

        if ($handler !== null) {
            /** @var callable $handler */
            // Retrieve the dataprovider
            $dataProvider = call_user_func($this->dataProvider[$methodName]);

            // retrieve handler method callable from method mapping
            /** @var callable $method */
            $method = call_user_func($handler);

            // execute handler method
            /** @var ResponderInterface $responder */
            $responder = call_user_func($method, $responseWriter, $dataProvider, $args);

            $response = $responseWriter->write($response, $responder);
        } else {
            $response = $responseWriter->writeError($response, ErrorCodeEnum::SERVER_VERSION_INCOMPATIBLE);
        }

        return $response;
    }

    /**
     * @param array<string, callable(): callable> $dataProvider
     */
    public static function init(
        FeatureSetFactoryInterface $featureSetFactory,
        AuthenticationProviderInterface $authenticationProvider,
        array $dataProvider,
    ): self {
        return new self(
            $featureSetFactory,
            $dataProvider,
            new ResponseWriterFactory(),
            new AuthenticationManager(
                $authenticationProvider,
            ),
        );
    }
}
