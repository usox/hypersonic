<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Mockery\Adapter\Phpunit\MockeryTestCase;

class PingResponderTest extends MockeryTestCase
{
    private PingResponder $subject;

    protected function setUp(): void
    {
        $this->subject = new PingResponder();
    }

    public function testIsBinaryResponderReturnsFalse(): void
    {
        $this->assertFalse(
            $this->subject->isBinaryResponder(),
        );
    }
}
