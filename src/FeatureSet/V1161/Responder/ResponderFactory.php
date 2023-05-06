<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Psr\Http\Message\StreamInterface;
use Traversable;
use Usox\HyperSonic\Response\ResponderInterface;

final class ResponderFactory implements ResponderFactoryInterface
{
    /**
     * @param array{
     *  ignoredArticles: string,
     *  index: array<array{
     *    name: string,
     *    artist: array<array{
     *      id: string,
     *      name: string,
     *      coverArt: string,
     *      artistImageUrl: string,
     *      albumCount: int,
     *      starred?: string
     *    }>
     *  }>
     * } $artistList
     */
    public function createArtistsResponder(
        array $artistList
    ): ResponderInterface {
        return new ArtistsResponder(
            $artistList,
        );
    }

    /**
     * @param array{
     *  valid: string,
     *  email: string,
     *  licenseExpires: string
     * } $licenseData
     */
    public function createLicenseResponder(
        array $licenseData
    ): ResponderInterface {
        return new LicenseResponder(
            $licenseData,
        );
    }

    public function createPingResponder(): ResponderInterface
    {
        return new PingResponder();
    }

    public function createCoverArtResponder(
        string $coverArt,
        string $contentType,
    ): ResponderInterface {
        return new CoverArtResponder(
            $coverArt,
            $contentType,
        );
    }

    /**
     * @param array{
     *  id: string,
     *  name: string,
     *  coverArt: string,
     *  albumCount: int,
     *  artistImageUrl: string,
     * } $artist
     * @param array<array{
     *  id: string,
     *  name: string,
     *  coverArt: string,
     *  songCount: int,
     *  created: string,
     *  duration: int,
     *  artist: string,
     *  artistId: string,
     *  year: string,
     *  genre: string,
     *  playCount: int
     * }> $albums
     */
    public function createArtistResponder(
        array $artist,
        array $albums,
    ): ResponderInterface {
        return new ArtistResponder($artist, $albums);
    }

    /**
     * @param array{
     *  id: string,
     *  name: string,
     *  coverArt: string,
     *  songCount: int,
     *  created: string,
     *  duration: int,
     *  artist: string,
     *  artistId: string,
     * } $album
     * @param array<array{
     *  id: string,
     *  isDir: bool,
     *  title: string,
     *  album: string,
     *  artist: string,
     *  track: int,
     *  coverArt: string,
     *  size: int,
     *  contentType: string,
     *  duration: int,
     *  created: string,
     *  albumId: string,
     *  artistId: string,
     *  playCount: int,
     * }> $songs
     */
    public function createAlbumResponder(
        array $album,
        array $songs,
    ): ResponderInterface {
        return new AlbumResponder(
            $album,
            $songs
        );
    }

    /**
     * @param array<array{
     *  value: string,
     *  albumCount: int,
     *  songCount: int
     * }> $genres
     */
    public function createGenresResponder(
        array $genres,
    ): ResponderInterface {
        return new GenresResponder(
            $genres
        );
    }

    /**
     * @param Traversable<array{
     *  name: string,
     *  id: string
     * }> $musicFolders
     */
    public function createMusicFoldersResponder(
        Traversable $musicFolders
    ): ResponderInterface {
        return new MusicFoldersResponder(
            $musicFolders
        );
    }

    /**
     * @param array{
     *  contentType: string,
     *  length: int,
     *  stream: StreamInterface,
     * } $streamData
     */
    public function createStreamResponder(
        array $streamData,
    ): ResponderInterface {
        return new StreamResponder(
            $streamData,
        );
    }

    /**
     * @param Traversable<array{
     *  id: string,
     *  name: string,
     *  coverArt: string,
     *  songCount: int,
     *  created: string,
     *  duration: int,
     *  artist: string,
     *  artistId: string,
     * }> $albumList
     */
    public function createAlbumList2Responder(
        Traversable $albumList
    ): ResponderInterface {
        return new AlbumList2Responder($albumList);
    }

    /**
     * @param Traversable<array{
     *  id: string,
     *  title: string,
     *  album: string,
     *  artist: string,
     *  coverArt: string,
     *  albumId: string,
     *  artistId: string,
     *  duration: int,
     *  created: string,
     *  starred: string,
     *  size: int
     * }> $songs
     * @param Traversable<array{
     *  id: string,
     *  name: string,
     *  artist: string,
     *  artistId: string,
     *  songCount: int,
     *  coverArt: string,
     *  duration: int,
     *  created: string,
     *  starred: string,
     *  year: int
     * }> $albums
     */
    public function createStarred2Responder(
        Traversable $songs,
        Traversable $albums,
    ): ResponderInterface {
        return new GetStarred2Responder(
            $songs,
            $albums
        );
    }

    /**
     * @param Traversable<array{
     *  id: string,
     *  parent: string,
     *  title: string,
     *  isDir: string,
     *  album: string,
     *  artist: string,
     *  track: int,
     *  year: int,
     *  coverArt: string,
     *  duration: int,
     *  size: int
     * }> $songs
     */
    public function createRandomSongsResponder(
        Traversable $songs
    ): ResponderInterface {
        return new GetRandomSongsResponder(
            $songs
        );
    }
}
