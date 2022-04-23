<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet;

interface FeatureSetInterface
{
    public function getVersion(): string;

    /**
     * @return array<string, callable(): object>
     */
    public function getMethods(): array;
}
