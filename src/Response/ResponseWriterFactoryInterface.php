<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

interface ResponseWriterFactoryInterface
{
    public function createXmlResponseWriter(
        string $apiVersion
    ): ResponseWriterInterface;

    public function createJsonResponseWriter(
        string $apiVersion
    ): ResponseWriterInterface;
}
