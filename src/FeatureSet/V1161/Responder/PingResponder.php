<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Usox\HyperSonic\Response\ResponderInterface;

final class PingResponder implements ResponderInterface
{
    public function writeXml(XMLArray $XMLArray): void
    {
    }

    public function writeJson(array &$root): void
    {
    }
}