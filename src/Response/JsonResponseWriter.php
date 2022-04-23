<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use Psr\Http\Message\ResponseInterface;
use Usox\HyperSonic\Exception\ErrorCodeEnum;

final class JsonResponseWriter implements ResponseWriterInterface
{
    public function __construct(
        private readonly string $apiVersion
    ) {
    }

    public function write(
        ResponseInterface $response,
        FormattedResponderInterface $responder
    ): ResponseInterface {
        $root = [
            'status' => 'ok',
            'version' => $this->apiVersion,
        ];
        $responder->writeJson($root);

        $response->getBody()->write(
            (string) json_encode(['subsonic-response' => $root], JSON_PRETTY_PRINT)
        );

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function writeError(
        ResponseInterface $response,
        ErrorCodeEnum $errorCode,
        string $message = ''
    ): ResponseInterface {
        $data = [
            'subsonic-response' => [
                'status' => 'failed',
                'version' => $this->apiVersion,
                'error' => [
                    'code' => $errorCode->value,
                    'message' => $message,
                ],
            ],
        ];

        $response->getBody()->write(
            (string) json_encode($data, JSON_PRETTY_PRINT)
        );

        return $response->withHeader('Content-Type', 'application/json');
    }
}
