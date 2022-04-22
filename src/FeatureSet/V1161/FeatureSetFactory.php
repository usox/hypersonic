<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

use Usox\HyperSonic\FeatureSet\V1161\Contract\ArtistListDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\LicenseDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\PingDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactory;

final class FeatureSetFactory implements FeatureSetFactoryInterface
{
    /**
     * @return array<string, callable(Contract\V1161DataProviderInterface): Method\V1161MethodInterface>
     */
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
    ): Method\V1161MethodInterface {
        return new Method\PingMethod(
            new ResponderFactory(),
            $pingDataProvider,
        );
    }

    public function createGetArtistsMethod(
        ArtistListDataProviderInterface $artistListDataProvider,
    ): Method\V1161MethodInterface {
        return new Method\GetArtistsMethod(
            new ResponderFactory(),
            $artistListDataProvider
        );
    }

    public function createGetLicense(
        LicenseDataProviderInterface $licenseDataProvider
    ): Method\V1161MethodInterface {
        return new Method\GetLicenseMethod(
            new ResponderFactory(),
            $licenseDataProvider,
        );
    }
}
