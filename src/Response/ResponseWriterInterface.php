<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use Psr\Http\Message\ResponseInterface;
use Usox\HyperSonic\Exception\ErrorCodeEnum;

interface ResponseWriterInterface
{
    public function write(
        ResponseInterface $response,
        FormattedResponderInterface $responder,
    ): ResponseInterface;

    public function writeError(
        ResponseInterface $response,
        ErrorCodeEnum $errorCode,
        string $message = ''
    ): ResponseInterface;
}
