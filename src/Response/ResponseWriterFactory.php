<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use AaronDDM\XMLBuilder\Writer\XMLWriterService;

final class ResponseWriterFactory implements ResponseWriterFactoryInterface
{
    public function createXmlResponseWriter(): ResponseWriterInterface
    {
        return new XmlResponseWriter(
            new XMLWriterService()
        );
    }

    public function createJsonResponseWriter(): ResponseWriterInterface
    {
        return new JsonResponseWriter();
    }
}
