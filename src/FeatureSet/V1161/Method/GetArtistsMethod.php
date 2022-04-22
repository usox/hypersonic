<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\FeatureSet\V1161\Contract\ArtistListDataProviderInterface;
use Usox\HyperSonic\Response\ResponseWriterInterface;

final class GetArtistsMethod implements V1161MethodInterface
{
    public function __construct(
        private readonly ArtistListDataProviderInterface $artistListDataProvider,
    ) {
    }

    /**
     * @param array<string, scalar> $args
     */
    public function __invoke(
        ResponseWriterInterface $responseWriter,
        array $args
    ): void {
        $data = [];
        $currentIndex = null;
        $indexItem = [];

        $musicFolderId = $args['musicFolderId'] ?? null;
        if ($musicFolderId !== null) {
            $musicFolderId = (string) $musicFolderId;
        }

        foreach ($this->artistListDataProvider->getArtists($musicFolderId) as $artist) {
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

        $responseWriter->writeArtistList(
            [
                'ignoredArticles' => implode(' ', $this->artistListDataProvider->getIgnoredArticles()),
                'index' => $data,
            ]
        );
    }
}
