<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

use DateTimeInterface;
use Traversable;

/**
 * @see http://www.subsonic.org/pages/api.jsp#getAlbumList2
 */
interface AlbumList2DataProviderInterface
{
    /**
     * If $orderType is `byGenre` or `byYear`, the optional values from $orderParameter MUST be considered
     *
     * @param array{
     *  year?: array{from: int, to: int},
     *  genre?: string
     * } $orderParameter
     *
     * @return Traversable<array{
     *  id: string,
     *  name: string,
     *  coverArtId: string,
     *  songCount: int,
     *  createDate: DateTimeInterface,
     *  duration: int,
     *  artistName: string,
     *  artistId: string,
     * }>
     */
    public function getAlbums(
        string $orderType,
        int $limit,
        int $offset,
        array $orderParameter,
        ?string $musicFolderId,
    ): Traversable;
}
