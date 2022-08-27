<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use Mockery\Adapter\Phpunit\MockeryTestCase;

class ResponseWriterFactoryTest extends MockeryTestCase
{
    private ResponseWriterFactory $subject;

    protected function setUp(): void
    {
        $this->subject = new ResponseWriterFactory();
    }

    public function testCreateXmlResponseWriterReturnsValue(): void
    {
        $this->assertInstanceOf(
            XmlResponseWriter::class,
            $this->subject->createXmlResponseWriter('6.6.6')
        );
    }

    public function testCreateJsonResponseWriterReturnsValue(): void
    {
        $this->assertInstanceOf(
            JsonResponseWriter::class,
            $this->subject->createJsonResponseWriter('6.6.6')
        );
    }
}
