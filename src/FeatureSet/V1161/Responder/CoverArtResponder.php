<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Psr\Http\Message\ResponseInterface;
use Usox\HyperSonic\Response\BinaryResponderInterface;

final class CoverArtResponder implements BinaryResponderInterface
{
    public function __construct(
        private readonly string $covertArt,
        private readonly string $contentType,
    ) {
    }

    public function isBinaryResponder(): bool
    {
        return true;
    }

    public function writeResponse(ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->covertArt);

        return $response->withHeader('Content-Type', $this->contentType);
    }
}
