<?php

namespace Usox\HyperSonic\Response;

use AaronDDM\XMLBuilder\XMLArray;

interface ResponderInterface
{
    public function writeXml(XMLArray $XMLArray): void;

    /**
     * @param array<mixed, mixed> $root
     */
    public function writeJson(array &$root): void;
}
