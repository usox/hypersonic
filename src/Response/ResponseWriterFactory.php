<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use AaronDDM\XMLBuilder\Writer\XMLWriterService;

final class ResponseWriterFactory implements ResponseWriterFactoryInterface
{
    public function createXmlResponseWriter(
        string $apiVersion
    ): ResponseWriterInterface {
        return new XmlResponseWriter(
            new XMLWriterService(),
            $apiVersion,
        );
    }

    public function createJsonResponseWriter(
        string $apiVersion
    ): ResponseWriterInterface {
        return new JsonResponseWriter(
            $apiVersion
        );
    }
}
