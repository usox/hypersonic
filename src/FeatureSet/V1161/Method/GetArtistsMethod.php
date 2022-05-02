<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\FeatureSet\V1161\Contract\ArtistListDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

final class GetArtistsMethod implements V1161MethodInterface
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
        ArtistListDataProviderInterface $artistListDataProvider,
        array $queryParams,
        array $args,
    ): ResponderInterface {
        $data = [];
        $currentIndex = null;
        $indexItem = [];

        $musicFolderId = $args['musicFolderId'] ?? null;
        if ($musicFolderId !== null) {
            $musicFolderId = (string) $musicFolderId;
        }

        foreach ($artistListDataProvider->getArtists($musicFolderId) as $artist) {
            $artistIndex = mb_strtoupper(substr($artist['name'], 0, 1));

            if ($artistIndex !== $currentIndex) {
                if ($indexItem !== []) {
                    $data[] = $indexItem;
                }

                $currentIndex = $artistIndex;

                $indexItem = [
                    'name' => $currentIndex,
                    'artist' => [],
                ];
            }

            $item = [
                'id' => $artist['id'],
                'name' => $artist['name'],
                'coverArt' => $artist['coverArtId'],
                'artistImageUrl' => $artist['artistImageUrl'],
                'albumCount' => $artist['albumCount'],
            ];

            if ($artist['starred'] !== null) {
                $item['starred'] = $artist['starred']->format(DATE_ATOM);
            }

            $indexItem['artist'][] = $item;
        }

        if ($indexItem !== []) {
            $data[] = $indexItem;
        }

        return $this->responderFactory->createArtistsResponder(
            [
                'ignoredArticles' => implode(' ', $artistListDataProvider->getIgnoredArticles()),
                'index' => $data,
            ]
        );
    }
}
