<?php

declare(strict_types=1);

namespace Usox\HyperSonic;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Usox\HyperSonic\Authentication\AuthenticationManager;
use Usox\HyperSonic\Authentication\AuthenticationManagerInterface;
use Usox\HyperSonic\Authentication\AuthenticationProviderInterface;
use Usox\HyperSonic\Authentication\Exception\AbstractAuthenticationException;
use Usox\HyperSonic\FeatureSet\V1161\FeatureSetFactoryInterface;
use Usox\HyperSonic\Response\ResponseWriterFactory;
use Usox\HyperSonic\Response\ResponseWriterFactoryInterface;

final class HyperSonic implements HyperSonicInterface
{
    /**
     * @param array{
     *  'ping.view'?: callable(Contract\PingDataProviderInterface): callable,
     *  'getLicense.view'?: callable(Contract\LicenseDataProviderInterface): callable,
     *  'getArtists.view'?: callable(Contract\ArtistListDataProviderInterface): callable,
     * } $dataProvider
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
        array $args
    ): ResponseInterface {
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
                40,
                $e->getMessage()
            );
        }

        $methods = array_intersect_key(
            $this->featureSetFactory->createMethodList(),
            $this->dataProvider
        );

        /** @var callable|null $handler */
        $handler = $methods[$args['methodName']] ?? null;

        if ($handler !== null) {
            /** @var callable $handler */
            $dataProvider = $this->dataProvider[$args['methodName']];

            $method = call_user_func($handler, call_user_func($dataProvider));

            call_user_func($method, $responseWriter, $args);

            $response = $responseWriter->write($response);
        } else {
            $response = $responseWriter->writeError($response, 30);
        }

        return $response;
    }

    /**
     * @param array<string, scalar> $args
     */
    public function run(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        return call_user_func($this, $request, $response, $args);
    }

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
