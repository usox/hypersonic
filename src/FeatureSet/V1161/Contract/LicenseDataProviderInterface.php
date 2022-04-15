<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

use DateTimeInterface;

interface LicenseDataProviderInterface
{
    public function isValid(): bool;

    public function getEmailAddress(): string;

    public function getExpiryDate(): DateTimeInterface;
}
