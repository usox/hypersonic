<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

use DateTimeInterface;

interface ArtistListDataProviderInterface extends V1161DataProviderInterface
{
    /**
     * @return array<string>
     */
    public function getIgnoredArticles(): array;

    /**
     * @return iterable<array{
     *  id: string,
     *  name: string,
     *  coverArtId: string,
     *  artistImageUrl: string,
     *  albumCount: int,
     *  starred: null|DateTimeInterface
     * }>
     */
    public function getArtists(
        ?string $musicFolderId,
    ): iterable;
}
