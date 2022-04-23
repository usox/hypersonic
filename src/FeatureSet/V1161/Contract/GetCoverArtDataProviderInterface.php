<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

interface GetCoverArtDataProviderInterface extends V1161DataProviderInterface
{
    /**
     * @return array{
     *  contentType: string,
     *  art: string
     * }
     */
    public function getArt(string $coverArtId): array;
}
