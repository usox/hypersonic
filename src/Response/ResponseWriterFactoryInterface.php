<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

interface ResponseWriterFactoryInterface
{
    public function createXmlResponseWriter(): ResponseWriterInterface;

    public function createJsonResponseWriter(): ResponseWriterInterface;
}