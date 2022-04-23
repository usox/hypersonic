<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

use Usox\HyperSonic\FeatureSet\FeatureSetInterface;

interface FeatureSetFactoryInterface extends FeatureSetInterface
{
    /**
     * @return array<string, callable(): Method\V1161MethodInterface>
     */
    public function getMethods(): array;

    public function createPingMethod(): Method\V1161MethodInterface;

    public function createGetArtistsMethod(): Method\V1161MethodInterface;

    public function createGetLicense(): Method\V1161MethodInterface;

    public function createGetCoverArtMethod(): Method\V1161MethodInterface;
}
