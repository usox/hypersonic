<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use AaronDDM\XMLBuilder\XMLArray;
use AaronDDM\XMLBuilder\XMLBuilder;
use Psr\Http\Message\ResponseInterface;
use Usox\HyperSonic\Exception\ErrorCodeEnum;

/**
 * Writes response data in xml format
 */
final class XmlResponseWriter implements ResponseWriterInterface
{
    private ?XMLArray $rootNode = null;

    public function __construct(
        private readonly XMLBuilder $xmlBuilder,
        private readonly string $apiVersion,
    ) {
    }

    /**
     * Write response for successful api requests
     */
    public function write(
        ResponseInterface $response,
        FormattedResponderInterface $responder,
    ): ResponseInterface {
        $rootNode = $this->getRootNode();

        $responder->writeXml($rootNode);

        $rootNode->end();

        return $this->writeToResponse($response);
    }

    /**
     * Write response for erroneous api requests
     */
    public function writeError(
        ResponseInterface $response,
        ErrorCodeEnum $errorCode,
        string $message = ''
    ): ResponseInterface {
        $this->xmlBuilder->createXMLArray()
            ->start(
                'subsonic-response',
                [
                    'xmlns' => 'http://subsonic.org/restapi',
                    'status' => 'failed',
                    'version' => $this->apiVersion,
                ]
            )
            ->add(
                'error',
                null,
                ['code' => $errorCode->value, 'message' => $message]
            )
            ->end();

        return $this->writeToResponse($response);
    }

    private function writeToResponse(
        ResponseInterface $response
    ): ResponseInterface {
        $response->getBody()->write(
            $this->xmlBuilder->getXML()
        );

        return $response->withHeader('Content-Type', 'application/xml');
    }

    private function getRootNode(): XMLArray
    {
        if ($this->rootNode === null) {
            $this->rootNode = $this->xmlBuilder->createXMLArray()
                ->start(
                    'subsonic-response',
                    [
                        'xmlns' => 'http://subsonic.org/restapi',
                        'status' => 'ok',
                        'version' => $this->apiVersion,
                    ]
                );
        }

        return $this->rootNode;
    }
}
