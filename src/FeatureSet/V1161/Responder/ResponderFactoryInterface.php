<?php

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Usox\HyperSonic\Response\ResponderInterface;

interface ResponderFactoryInterface
{
    public function createArtistsResponder(
        array $artistList
    ): ResponderInterface;

    public function createLicenseResponder(
        array $licenseData
    ): ResponderInterface;

    public function createPingResponder(): ResponderInterface;
}