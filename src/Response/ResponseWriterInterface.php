<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use Psr\Http\Message\ResponseInterface;
use Usox\HyperSonic\Exception\ErrorCodeEnum;

interface ResponseWriterInterface
{
    /**
     * Write response for successful api requests
     */
    public function write(
        ResponseInterface $response,
        FormattedResponderInterface $responder,
    ): ResponseInterface;

    /**
     * Write response for erroneous api requests
     */
    public function writeError(
        ResponseInterface $response,
        ErrorCodeEnum $errorCode,
        string $message = ''
    ): ResponseInterface;
}
