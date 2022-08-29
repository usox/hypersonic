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
}
