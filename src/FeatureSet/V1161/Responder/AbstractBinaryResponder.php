<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Usox\HyperSonic\Response\BinaryResponderInterface;

abstract class AbstractBinaryResponder implements BinaryResponderInterface
{
    public function isBinaryResponder(): bool
    {
        return true;
    }
}
