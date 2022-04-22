<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use Psr\Http\Message\ResponseInterface;
use Usox\HyperSonic\Exception\ErrorCodeEnum;

final class JsonResponseWriter implements ResponseWriterInterface
{
    /** @var array{status: string, version: string} */
    private array $root = [
        'status' => 'ok',
        'version' => '1.16.1',
    ];

    public function write(
        ResponseInterface $response,
        ResponderInterface $responder
    ): ResponseInterface {
        $responder->writeJson($this->root);

        $response->getBody()->write(
            (string) json_encode(['subsonic-response' => $this->root], JSON_PRETTY_PRINT)
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
                'version' => '1.16.1',
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
