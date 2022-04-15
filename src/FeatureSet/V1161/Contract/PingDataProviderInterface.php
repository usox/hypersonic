<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

interface PingDataProviderInterface
{
    public function isOk(): bool;
}
