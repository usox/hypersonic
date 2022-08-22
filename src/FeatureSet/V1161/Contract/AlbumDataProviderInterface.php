<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

use DateTimeInterface;

interface AlbumDataProviderInterface extends V1161DataProviderInterface
{
    /**
     * @return null|array{
     *  id: string,
     *  name: string,
     *  coverArtId: string,
     *  songCount: int,
     *  createDate: DateTimeInterface,
     *  duration: int,
     *  artistName: string,
     *  artistId: string,
     *  songs: array<array{
     *     id: string,
     *     isDir: bool,
     *     name: string,
     *     albumName: string,
     *     artistName: string,
     *     trackNumber: int,
     *     coverArtId: string,
     *     size: int,
     *     contentType: string,
     *     duration: int,
     *     createDate: DateTimeInterface,
     *     albumId: string,
     *     artistId: string,
     *     playCount: int,
     *  }>
     * }
     */
    public function getAlbum(
        string $albumId
    ): ?array;
}
