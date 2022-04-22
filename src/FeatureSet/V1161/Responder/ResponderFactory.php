<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Usox\HyperSonic\Response\ResponderInterface;

final class ResponderFactory implements ResponderFactoryInterface
{
    public function createArtistsResponder(
        array $artistList
    ): ResponderInterface {
        return new ArtistsResponder(
            $artistList,
        );
    }

    public function createLicenseResponder(
        array $licenseData
    ): ResponderInterface {
        return new LicenseResponder(
            $licenseData,
        );
    }

    public function createPingResponder(): ResponderInterface
    {
        return new PingResponder();
    }
}
