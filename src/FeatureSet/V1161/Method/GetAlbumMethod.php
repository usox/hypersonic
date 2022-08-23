<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\Exception\ErrorCodeEnum;
use Usox\HyperSonic\Exception\MethodCallFailedException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\AlbumDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

/**
 * Retrieves and transforms data for a single album
 *
 * This class covers the `getAlbum.view` method
 */
final class GetAlbumMethod implements V1161MethodInterface
{
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
        AlbumDataProviderInterface $albumDataProvider,
        array $queryParams,
        array $args,
    ): ResponderInterface {
        $albumId = (string) ($queryParams['id'] ?? null);

        $album = $albumDataProvider->getAlbum($albumId);

        if ($album === null) {
            throw new MethodCallFailedException(
                ErrorCodeEnum::NOT_FOUND
            );
        }

        return $this->responderFactory->createAlbumResponder(
            [
                'id' => $album['id'],
                'name' => $album['name'],
                'coverArt' => $album['coverArtId'],
                'songCount' => $album['songCount'],
                'created' => $album['createDate']->format(DATE_ATOM),
                'duration' => $album['duration'],
                'artist' => $album['artistName'],
                'artistId' => $album['artistId'],
            ],
            array_values(
                array_map(
                    fn(array $song): array => [
                        'id' => $song['id'],
                        'isDir' => $song['isDir'],
                        'title' => $song['name'],
                        'album' => $song['albumName'],
                        'artist' => $song['artistName'],
                        'track' => $song['trackNumber'],
                        'coverArt' => $song['coverArtId'],
                        'size' => $song['size'],
                        'contentType' => $song['contentType'],
                        'duration' => $song['duration'],
                        'created' => $song['createDate']->format(DATE_ATOM),
                        'albumId' => $song['albumId'],
                        'artistId' => $song['artistId'],
                        'playCount' => $song['playCount'],
                    ],
                    $album['songs']
                )
            )
        );
    }
}
