<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use AaronDDM\XMLBuilder\Writer\XMLWriterService;
use AaronDDM\XMLBuilder\XMLBuilder;

/**
 * Create the response writes classes depending on the requested format
 */
final class ResponseWriterFactory implements ResponseWriterFactoryInterface
{
    public function createXmlResponseWriter(
        string $apiVersion,
    ): ResponseWriterInterface {
        return new XmlResponseWriter(
            new XMLBuilder(
                new XMLWriterService(),
            ),
            $apiVersion,
        );
    }

    public function createJsonResponseWriter(
        string $apiVersion,
    ): ResponseWriterInterface {
        return new JsonResponseWriter(
            $apiVersion,
        );
    }
}
