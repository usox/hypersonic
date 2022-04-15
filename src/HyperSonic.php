<?php

declare(strict_types=1);

namespace Usox\HyperSonic;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Usox\HyperSonic\FeatureSet\V1161\FeatureSetInterface;
use Usox\HyperSonic\Response\ResponseWriterFactory;
use Usox\HyperSonic\Response\ResponseWriterFactoryInterface;

final class HyperSonic implements HyperSonicInterface
{
    public function __construct(
        private FeatureSetInterface $featureSet,
        private ResponseWriterFactoryInterface $responseWriterFactory,
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
        $methods = $this->featureSet->getMethods();

        $responseFormat = $request->getQueryParams()['f'] ?? 'xml';

        if ($responseFormat === 'xml') {
            $responseWriter = $this->responseWriterFactory->createXmlResponseWriter();
        } else {
            $responseWriter = $this->responseWriterFactory->createJsonResponseWriter();
        }

        /** @var callable|null $handler */
        $handler = $methods[$args['methodName']] ?? null;
        if ($handler !== null) {
            call_user_func($handler, $responseWriter, $args);

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
        FeatureSetInterface $featureSet,
    ): self {
        return new self(
            $featureSet,
            new ResponseWriterFactory(),
        );
    }
}
