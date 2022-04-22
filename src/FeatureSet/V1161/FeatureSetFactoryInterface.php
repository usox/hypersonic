<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

interface FeatureSetFactoryInterface
{
    /**
     * @return array<string, callable(): Method\V1161MethodInterface>
     */
    public function getMethods(): array;

    public function createPingMethod(): Method\V1161MethodInterface;

    public function createGetArtistsMethod(): Method\V1161MethodInterface;

    public function createGetLicense(): Method\V1161MethodInterface;
}
