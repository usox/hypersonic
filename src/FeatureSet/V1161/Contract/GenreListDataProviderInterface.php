<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

use Traversable;

interface GenreListDataProviderInterface extends V1161DataProviderInterface
{
    /**
     * @return Traversable<array{
     *  value: string,
     *  albumCount: int,
     *  songCount: int
     * }>
     */
    public function getGenres(): Traversable;
}
