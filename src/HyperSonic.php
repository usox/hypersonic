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
use Usox\HyperSonic\FeatureSet\FeatureSetMethodInterface;
use Usox\HyperSonic\FeatureSet\V1161\FeatureSetFactoryInterface;
use Usox\HyperSonic\Response\BinaryResponderInterface;
use Usox\HyperSonic\Response\FormattedResponderInterface;
use Usox\HyperSonic\Response\ResponderInterface;
use Usox\HyperSonic\Response\ResponseWriterFactory;
use Usox\HyperSonic\Response\ResponseWriterFactoryInterface;

final readonly class HyperSonic implements HyperSonicInterface
{
    /**
     * @param array<string, callable(): FeatureSetMethodInterface> $dataProvider
     */
    public function __construct(
        private FeatureSetFactoryInterface $featureSetFactory,
        private array $dataProvider,
        private ResponseWriterFactoryInterface $responseWriterFactory,
        private AuthenticationManagerInterface $authenticationManager,
    ) {
    }

    /**
     * @param array<string, scalar> $args
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ): ResponseInterface {
        return $this->run($request, $response, $args);
    }

    /**
     * @param array<string, scalar> $args
     */
    public function run(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ): ResponseInterface {
        $queryParams = $request->getQueryParams();

        // subsonic response format
        $responseFormat = $queryParams['f'] ?? 'xml';

        if ($responseFormat === 'xml') {
            $responseWriter = $this->responseWriterFactory->createXmlResponseWriter(
                $this->featureSetFactory->getVersion(),
            );
        } else {
            $responseWriter = $this->responseWriterFactory->createJsonResponseWriter(
                $this->featureSetFactory->getVersion(),
            );
        }

        try {
            $this->authenticationManager->auth($request);
        } catch (AbstractAuthenticationException $authenticationException) {
            return $responseWriter->writeError(
                $response,
                ErrorCodeEnum::WRONG_USERNAME_OR_PASSWORD,
                $authenticationException->getMessage(),
            );
        }

        // ensure we filter all possible methods by the really implemented ones
        $methods = array_intersect_key(
            $this->featureSetFactory->getMethods(),
            $this->dataProvider,
        );

        $methodName = $args['methodName'];

        /** @var callable():FeatureSetMethodInterface|null $handler */
        $handler = $methods[$methodName] ?? null;

        if ($handler !== null) {
            // Retrieve the data provider
            $dataProvider = call_user_func($this->dataProvider[$methodName]);

            // retrieve handler method callable from method mapping
            /** @var FeatureSetMethodInterface&callable(object,array<string, mixed>,array<string, mixed>):ResponderInterface $method */
            $method = call_user_func($handler);

            // execute handler method
            /** @var ResponderInterface $responder */
            $responder = call_user_func($method, $dataProvider, $queryParams, $args);
            if ($responder->isBinaryResponder()) {
                /** @var BinaryResponderInterface $responder */
                $response = $responder->writeResponse($response);
            } else {
                /** @var FormattedResponderInterface $responder */
                $response = $responseWriter->write($response, $responder);
            }
        } else {
            $response = $responseWriter->writeError($response, ErrorCodeEnum::SERVER_VERSION_INCOMPATIBLE);
        }

        return $response;
    }

    /**
     * Create a hypersonic instance
     *
     * Expects a list of so called `data providers` which consists
     * of a key (the method name) and a callable which creates the
     * data provider. See documentation
     *
     * @param array<string, callable(): FeatureSetMethodInterface> $dataProvider
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
