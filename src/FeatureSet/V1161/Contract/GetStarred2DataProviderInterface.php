<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

use DateTimeInterface;
use Traversable;

interface GetStarred2DataProviderInterface extends V1161DataProviderInterface
{
    /**
     * @return array{
     *  songs: Traversable<array{
     *    id: int|string,
     *    name: string,
     *    artistName: string,
     *    artistId: int|string,
     *    albumName: string,
     *    albumId: int|string,
     *    coverArtId: string,
     *    length: int,
     *    createDate: DateTimeInterface,
     *    starredDate: DateTimeInterface,
     *    filesize: int
     *  }>,
     *  albums: Traversable<array{
     *    id: int|string,
     *    name: string,
     *    artistName: string,
     *    coverArtId: string,
     *    artistId: int|string,
     *    length: int,
     *    createDate: DateTimeInterface,
     *    starredDate: DateTimeInterface,
     *    songCount: int,
     *    year: int
     *  }>
     * }
     */
    public function getStarred(
        ?string $musicFolderId,
    ): array;
}
