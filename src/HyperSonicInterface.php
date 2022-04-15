<?php

declare(strict_types=1);

namespace Usox\HyperSonic;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface HyperSonicInterface
{
    /**
     * @param array<string, scalar> $args
     */
    public function run(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface;
}