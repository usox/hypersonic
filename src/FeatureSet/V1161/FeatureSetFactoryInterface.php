<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

use Usox\HyperSonic\FeatureSet\V1161\Contract\ArtistListDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\LicenseDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\PingDataProviderInterface;

interface FeatureSetFactoryInterface
{
    /**
     * @return array<string, callable(Contract\V1161DataProviderInterface): Method\V1161MethodInterface>
     */
    public function createMethodList(): array;

    public function createPingMethod(
        PingDataProviderInterface $pingDataProvider
    ): Method\V1161MethodInterface;

    public function createGetArtistsMethod(
        ArtistListDataProviderInterface $artistListDataProvider
    ): Method\V1161MethodInterface;

    public function createGetLicense(
        LicenseDataProviderInterface $licenseDataProvider
    ): Method\V1161MethodInterface;
}