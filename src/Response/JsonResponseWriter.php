<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use Psr\Http\Message\ResponseInterface;
use Usox\HyperSonic\Exception\ErrorCodeEnum;

/**
 * Writes response data in json format
 */
final readonly class JsonResponseWriter implements ResponseWriterInterface
{
    public function __construct(
        private string $apiVersion,
    ) {
    }

    /**
     * Write response for successful api requests
     */
    public function write(
        ResponseInterface $response,
        FormattedResponderInterface $responder,
    ): ResponseInterface {
        $root = [
            'status' => 'ok',
            'version' => $this->apiVersion,
        ];

        $responder->writeJson($root);

        return $this->writeToResponse(
            $response,
            $root,
        );
    }

    /**
     * Write response for erroneous api requests
     */
    public function writeError(
        ResponseInterface $response,
        ErrorCodeEnum $errorCode,
        string $message = '',
    ): ResponseInterface {
        return $this->writeToResponse(
            $response,
            [
                'status' => 'failed',
                'version' => $this->apiVersion,
                'error' => [
                    'code' => $errorCode->value,
                    'message' => $message,
                ],
            ],
        );
    }

    /**
     * @param array<mixed> $data
     */
    private function writeToResponse(
        ResponseInterface $response,
        array $data,
    ): ResponseInterface {
        $response->getBody()->write(
            (string) json_encode(
                ['subsonic-response' => $data],
                JSON_PRETTY_PRINT,
            ),
        );

        return $response->withHeader('Content-Type', 'application/json');
    }
}
