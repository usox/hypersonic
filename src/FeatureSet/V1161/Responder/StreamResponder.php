<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class StreamResponder extends AbstractBinaryResponder
{
    /**
     * @param array{
     *  contentType: string,
     *  stream: StreamInterface,
     *  estimatedContentLength?: int
     * } $streamData
     */
    public function __construct(
        private readonly array $streamData,
    ) {
    }

    public function writeResponse(ResponseInterface $response): ResponseInterface
    {
        if (array_key_exists('estimatedContentLength', $this->streamData)) {
            $response = $response->withHeader(
                'Content-Length',
                (string) $this->streamData['estimatedContentLength'],
            );
        }

        return $response
            ->withBody($this->streamData['stream'])
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Cache-Control', 'no-cache')
            ->withHeader('Content-Type', $this->streamData['contentType']);
    }
}
