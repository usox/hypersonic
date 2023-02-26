<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

use Usox\HyperSonic\FeatureSet\FeatureSetInterface;
use Usox\HyperSonic\FeatureSet\FeatureSetMethodInterface;

interface FeatureSetFactoryInterface extends FeatureSetInterface
{
    /**
     * @return array<string, callable(): FeatureSetMethodInterface>
     */
    public function getMethods(): array;
}
