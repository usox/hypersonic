<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

use DateTimeInterface;

interface ArtistDataProviderInterface extends V1161DataProviderInterface
{
    /**
     * @return null|array{
     *  id: int|string,
     *  name: string,
     *  coverArtId: string,
     *  albumCount: int,
     *  artistImageUrl: string,
     *  albums: array<array{
     *    id: int|string,
     *    name: string,
     *    coverArtId: string,
     *    songCount: int,
     *    createDate: DateTimeInterface|null,
     *    duration: int,
     *    artistName: string,
     *    artistId: int|string,
     *    year?: string,
     *    genre?: string,
     *    playCount?: int
     *  }>
     * }
     */
    public function getArtist(
        string $artistId
    ): ?array;
}
