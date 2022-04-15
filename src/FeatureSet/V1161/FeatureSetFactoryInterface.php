<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

use Usox\HyperSonic\FeatureSet\V1161\Contract\ArtistListDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\LicenseDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\PingDataProviderInterface;

interface FeatureSetFactoryInterface
{
    public function createMethodList(): array;

    public function createPingMethod(PingDataProviderInterface $pingDataProvider);

    public function createGetArtistsMethod(ArtistListDataProviderInterface $artistListDataProvider,);

    public function createGetLicense(LicenseDataProviderInterface $licenseDataProvider);
}