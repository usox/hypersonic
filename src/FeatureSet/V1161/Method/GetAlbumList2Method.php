<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use DateTimeInterface;
use Generator;
use Traversable;
use Usox\HyperSonic\Exception\ErrorCodeEnum;
use Usox\HyperSonic\Exception\MethodCallFailedException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\AlbumList2DataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

/**
 * Retrieve and transforms album data for the album list
 *
 * This class covers the `getAlbumList2.view` method
 *
 * @see http://www.subsonic.org/pages/api.jsp#getAlbumList2
 */
final class GetAlbumList2Method implements V1161MethodInterface
{
    /**
     * List of allowed types for ordering
     *
     * @var string[]
     */
    private const ORDER_TYPES = [
        'random',
        'newest',
        'frequent',
        'recent',
        'starred',
        'alphabeticalByName',
        'alphabeticalByArtist',
        'byYear',
        'byGenre',
    ];

    /**
     * @var int
     */
    private const DEFAULT_LIMIT = 10;

    /**
     * @var int
     */
    private const MAX_LIMIT = 500;

    public function __construct(
        private readonly ResponderFactoryInterface $responderFactory,
    ) {
    }

    /**
     * @param array<string, scalar> $queryParams
     * @param array<string, scalar> $args
     *
     * @throws MethodCallFailedException
     */
    public function __invoke(
        AlbumList2DataProviderInterface $albumList2DataProvider,
        array $queryParams,
        array $args,
    ): ResponderInterface {
        $type = $queryParams['type'] ?? '';
        $limit = min((int) ($queryParams['size'] ?? self::DEFAULT_LIMIT), self::MAX_LIMIT);
        $offset = (int) ($queryParams['offset'] ?? 0);
        $musicFolderId = $queryParams['musicFolderId'] ?? null;

        if ($musicFolderId !== null) {
            $musicFolderId = (string) $musicFolderId;
        }

        if (!in_array($type, self::ORDER_TYPES, true)) {
            throw new MethodCallFailedException(
                ErrorCodeEnum::MISSING_PARAMETER
            );
        }

        $orderParameter = [];

        if ($type === 'byYear') {
            $fromYear = $queryParams['fromYear'] ?? null;
            $toYear = $queryParams['toYear'] ?? null;

            if ($fromYear === null || $toYear === null) {
                throw new MethodCallFailedException(
                    ErrorCodeEnum::MISSING_PARAMETER
                );
            }

            $orderParameter = ['year' => ['from' => (int) $fromYear, 'to' => (int) $toYear]];
        }

        if ($type === 'byGenre') {
            $genre = $queryParams['genre'] ?? null;
            if ($genre === null) {
                throw new MethodCallFailedException(
                    ErrorCodeEnum::MISSING_PARAMETER
                );
            }

            $orderParameter = ['genre' => (string) $genre];
        }

        $albumList = $albumList2DataProvider->getAlbums(
            $type,
            $limit,
            $offset,
            $orderParameter,
            $musicFolderId,
        );

        return $this->responderFactory->createAlbumList2Responder(
            $this->transformAlbums($albumList)
        );
    }

    /**
     * @param Traversable<array{
     *  id: string,
     *  name: string,
     *  coverArtId: string,
     *  songCount: int,
     *  createDate: DateTimeInterface,
     *  duration: int,
     *  artistName: string,
     *  artistId: string,
     * }> $albumList
     *
     * @return Generator<array{
     *  id: string,
     *  name: string,
     *  coverArt: string,
     *  songCount: int,
     *  created: string,
     *  duration: int,
     *  artist: string,
     *  artistId: string,
     * }>
     */
    private function transformAlbums(Traversable $albumList): Generator
    {
        foreach ($albumList as $album) {
            yield [
                'id' => $album['id'],
                'name' => $album['name'],
                'coverArt' => $album['coverArtId'],
                'songCount' => $album['songCount'],
                'created' => $album['createDate']->format(DATE_ATOM),
                'duration' => $album['duration'],
                'artist' => $album['artistName'],
                'artistId' => $album['artistId'],
            ];
        }
    }
}
