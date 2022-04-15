<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

use Usox\HyperSonic\FeatureSet\V1161\Contract\ArtistListDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\LicenseDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\PingDataProviderInterface;

final class FeatureSetFactory implements FeatureSetFactoryInterface
{
    public function createMethodList(): array
    {
        return [
            'ping.view' => fn (PingDataProviderInterface $pingDataProvider) =>
                $this->createPingMethod($pingDataProvider),
            'getLicense.view' => fn (LicenseDataProviderInterface $licenseDataProvider) =>
                $this->createGetLicense($licenseDataProvider),
            'getArtists.view' => fn (ArtistListDataProviderInterface $artistListDataProvider) =>
                $this->createGetArtistsMethod($artistListDataProvider),
        ];
    }

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
