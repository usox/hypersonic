<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\Exception\ErrorCodeEnum;
use Usox\HyperSonic\Exception\MethodCallFailedException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\ArtistDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

/**
 * Retrieves and transforms data for a single artist
 *
 * This class covers the `getArtist.view` method
 */
final class GetArtistMethod implements V1161MethodInterface
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
        ArtistDataProviderInterface $artistDataProvider,
        array $queryParams,
        array $args,
    ): ResponderInterface {
        $artistId = (string) ($queryParams['id'] ?? null);

        $artist = $artistDataProvider->getArtist($artistId);

        if ($artist === null) {
            throw new MethodCallFailedException(
                ErrorCodeEnum::NOT_FOUND
            );
        }

        return $this->responderFactory->createArtistResponder(
            [
                'id' => (string) $artist['id'],
                'name' => $artist['name'],
                'coverArt' => $artist['coverArtId'],
                'artistImageUrl' => $artist['artistImageUrl'],
                'albumCount' => $artist['albumCount'],
            ],
            array_map(
                fn (array $album): array => [
                    'id' => (string) $album['id'],
                    'name' => $album['name'],
                    'artist' => $album['artistName'],
                    'artistId' => (string) $album['artistId'],
                    'coverArt' => $album['coverArtId'],
                    'songCount' => $album['songCount'],
                    'duration' => $album['duration'],
                    'playCount' => $album['playCount'] ?? 0,
                    'created' => (string) $album['createDate']?->format(DATE_ATOM),
                    'year' => $album['year'] ?? '',
                    'genre' => $album['genre'] ?? '',
                ],
                $artist['albums']
            )
        );
    }
}
