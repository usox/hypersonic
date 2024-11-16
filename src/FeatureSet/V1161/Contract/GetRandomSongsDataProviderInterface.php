<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

use Traversable;

interface GetRandomSongsDataProviderInterface extends V1161DataProviderInterface
{
    /**
     * Returns a list of random songs
     *
     * @param string|null $genreName Name of the requested genre, e.g. "Symphonic Thrash Metal"
     *
     * @return Traversable<array{
     *  id: int|string,
     *  name: string,
     *  albumId: int|string,
     *  artistName: string,
     *  albumName: string,
     *  trackNumber: int,
     *  year: int,
     *  genre: string,
     *  coverArtId: string,
     *  length: int,
     *  filesize: int
     *  }>
     */
    public function getRandomSongs(
        ?string $musicFolderId,
        ?int $limit = 10,
        ?string $genreName = null,
        ?int $fromYear = null,
        ?int $toYear = null,
    ): Traversable;
}
