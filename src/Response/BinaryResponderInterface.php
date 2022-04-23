<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use Psr\Http\Message\ResponseInterface;

interface BinaryResponderInterface extends ResponderInterface
{
    public function writeResponse(ResponseInterface $response): ResponseInterface;
}
