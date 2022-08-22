<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Psr\Http\Message\ResponseInterface;

final class CoverArtResponder extends AbstractBinaryResponder
{
    public function __construct(
        private readonly string $covertArt,
        private readonly string $contentType,
    ) {
    }

    public function writeResponse(ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->covertArt);

        return $response->withHeader('Content-Type', $this->contentType);
    }
}
