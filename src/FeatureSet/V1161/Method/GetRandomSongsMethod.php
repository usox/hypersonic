<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Generator;
use Traversable;
use Usox\HyperSonic\FeatureSet\V1161\Contract\GetRandomSongsDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

/**
 * Retrieve random songs
 *
 * This class covers the `getRandomSongs.view` method
 *
 * @see http://www.subsonic.org/pages/api.jsp#getRandomSongs
 */
final class GetRandomSongsMethod implements V1161MethodInterface
{
    /** @var int */
    private const MAX_SONGS = 500;

    public function __construct(
        private readonly ResponderFactoryInterface $responderFactory
    ) {
    }

    /**
     * @param array{
     *  musicFolderId?: int|string,
     *  size?: int|string,
     *  genre?: string,
     *  fromYear?: int|string,
     *  toYear?: int|string
     * } $queryParams
     * @param array<string, scalar> $args
     */
    public function __invoke(
        GetRandomSongsDataProviderInterface $dataProvider,
        array $queryParams,
        array $args,
    ): ResponderInterface {
        $musicFolderId = (string) ($queryParams['musicFolderId'] ?? '');
        $limit = (int) ($queryParams['size'] ?? 10);
        $genre = $queryParams['genre'] ?? null;
        $fromYear = (int) ($queryParams['fromYear'] ?? 0);
        $toYear = (int) ($queryParams['toYear'] ?? 0);

        if ($musicFolderId === '') {
            $musicFolderId = null;
        }

        if ($fromYear === 0) {
            $fromYear = null;
        }

        if ($toYear === 0) {
            $toYear = null;
        }

        if ($limit > self::MAX_SONGS) {
            $limit = self::MAX_SONGS;
        }

        return $this->responderFactory->createRandomSongsResponder(
            $this->buildSongList(
                $dataProvider->getRandomSongs(
                    $musicFolderId,
                    $limit,
                    $genre,
                    $fromYear,
                    $toYear
                )
            )
        );
    }

    /**
     * @param Traversable<array{
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
     * }> $songs
     *
     * @return Generator<array{
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
     * }>
     */
    private function buildSongList(Traversable $songs): Generator
    {
        foreach ($songs as $song) {
            yield [
                'id' => (string) $song['id'],
                'parent' => (string) $song['albumId'],
                'title' => $song['name'],
                'isDir' => 'false',
                'album' => $song['albumName'],
                'artist' => $song['artistName'],
                'track' => $song['trackNumber'],
                'year' => $song['year'],
                'genre' => $song['genre'],
                'coverArt' => $song['coverArtId'],
                'duration' => $song['length'],
                'size' => $song['filesize'],
            ];
        }
    }
}
