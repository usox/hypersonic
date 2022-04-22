<?php

namespace Usox\HyperSonic\Response;

use AaronDDM\XMLBuilder\XMLArray;

interface ResponderInterface
{
    public function writeXml(XMLArray $XMLArray): void;

    public function writeJson(array &$root): void;
}