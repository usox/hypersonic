<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

final class FeatureSet1161 implements FeatureSetInterface
{
    /**
     * @param array{
     *  'ping.view' => callable(): Contract\PingDataProviderInterface,
     *  'getLicense.view' => callable(): Contract\LicenseDataProviderInterface,
     * } $methods
     */
    public function __construct(
        private array $methods = []
    ) {
    }

    public function getMethods(): array {
        return $this->methods;
    }
}
