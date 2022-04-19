<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

interface PingDataProviderInterface extends V1161DataProviderInterface
{
    public function isOk(): bool;
}
