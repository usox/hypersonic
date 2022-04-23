<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Usox\HyperSonic\Response\FormattedResponderInterface;

final class PingResponder implements FormattedResponderInterface
{
    public function writeXml(XMLArray $XMLArray): void
    {
    }

    public function writeJson(array &$root): void
    {
    }

    public function isBinaryResponder(): bool
    {
        return false;
    }
}
