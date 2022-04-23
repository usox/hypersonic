<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use AaronDDM\XMLBuilder\XMLArray;

interface FormattedResponderInterface extends ResponderInterface
{
    public function writeXml(XMLArray $XMLArray): void;

    /**
     * @param array<mixed, mixed> $root
     */
    public function writeJson(array &$root): void;
}
