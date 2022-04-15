<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

use Usox\HyperSonic\FeatureSet\V1161\Contract\ArtistListDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\LicenseDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\PingDataProviderInterface;

final class MethodFactory implements MethodFactoryInterface
{
    public function createPingMethod(
        PingDataProviderInterface $pingDataProvider
    ) {
        return new Method\PingMethod(
            $pingDataProvider,
        );
    }

    public function createGetArtistsMethod(
        ArtistListDataProviderInterface $artistListDataProvider,
    ) {
        return new Method\GetArtistsMethod(
            $artistListDataProvider
        );
    }

    public function createGetLicense(
        LicenseDataProviderInterface $licenseDataProvider
    ) {
        return new Method\GetLicenseMethod(
            $licenseDataProvider,
        );
    }
}
