<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use DateTimeInterface;
use Generator;
use Traversable;
use Usox\HyperSonic\FeatureSet\V1161\Contract\GetStarred2DataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

/**
 * Retrieve and transforms starred data
 *
 * This class covers the `getStarred2.view` method
 *
 * @see http://www.subsonic.org/pages/api.jsp#getStarred2
 */
final class GetStarred2Method implements V1161MethodInterface
{
    public function __construct(
        private readonly ResponderFactoryInterface $responderFactory,
    ) {
    }

    /**
     * @param array<string, scalar> $queryParams
     * @param array<string, scalar> $args
     */
    public function __invoke(
        GetStarred2DataProviderInterface $getStarred2DataProvider,
        array $queryParams,
        array $args,
    ): ResponderInterface {
        $musicFolderId = (string) ($queryParams['musicFolderId'] ?? '');
        if ($musicFolderId === '') {
            $musicFolderId = null;
        }

        $data = $getStarred2DataProvider->getStarred($musicFolderId);

        return $this->responderFactory->createStarred2Responder(
            $this->buildSongList($data['songs']),
            $this->buildAlbumsList($data['albums']),
        );
    }

    /**
     * @param Traversable<array{
     *   id: int|string,
     *   name: string,
     *   artistName: string,
     *   coverArtId: string,
     *   artistId: int|string,
     *   length: int,
     *   createDate: DateTimeInterface,
     *   starredDate: DateTimeInterface,
     *   songCount: int,
     *   year: int
     * }> $albums
     *
     * @return Generator<array{
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
     * }>
     */
    private function buildAlbumsList(Traversable $albums): Generator
    {
        foreach ($albums as $album) {
            yield [
                'id' => (string) $album['id'],
                'name' => $album['name'],
                'artist' => $album['artistName'],
                'artistId' => (string) $album['artistId'],
                'songCount' => $album['songCount'],
                'coverArt' => $album['coverArtId'],
                'duration' => $album['length'],
                'created' => $album['createDate']->format(DATE_ATOM),
                'starred' => $album['starredDate']->format(DATE_ATOM),
                'year' => $album['year'],
            ];
        }
    }

    /**
     * @param Traversable<array{
     *  id: int|string,
     *  name: string,
     *  artistName: string,
     *  artistId: int|string,
     *  albumName: string,
     *  albumId: int|string,
     *  coverArtId: string,
     *  length: int,
     *  createDate: DateTimeInterface,
     *  starredDate: DateTimeInterface,
     *  filesize: int
     * }> $songs
     *
     * @return Generator<array{
     *  id: string,
     *  name: string,
     *  album: string,
     *  artist: string,
     *  coverArt: string,
     *  albumId: string,
     *  artistId: string,
     *  duration: int,
     *  created: string,
     *  starred: string,
     *  size: int
     * }>
     */
    private function buildSongList(Traversable $songs): Generator
    {
        foreach ($songs as $song) {
            yield [
                'id' => (string) $song['id'],
                'name' => $song['name'],
                'album' => $song['albumName'],
                'artist' => $song['artistName'],
                'coverArt' => $song['coverArtId'],
                'albumId' => (string) $song['albumId'],
                'artistId' => (string) $song['artistId'],
                'duration' => $song['length'],
                'created' => $song['createDate']->format(DATE_ATOM),
                'starred' => $song['starredDate']->format(DATE_ATOM),
                'size' => $song['filesize'],
            ];
        }
    }
}
