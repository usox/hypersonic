<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Usox\HyperSonic\Response\FormattedResponderInterface;

final readonly class ArtistsResponder implements FormattedResponderInterface
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
    public function __construct(
        private array $artistList,
    ) {
    }

    public function writeXml(XMLArray $XMLArray): void
    {
        $XMLArray->startLoop(
            'artists',
            ['ignoredArticles' => $this->artistList['ignoredArticles']],
            function (XMLArray $XMLArray): void {
                foreach ($this->artistList['index'] as $indexItem) {
                    $XMLArray->startLoop(
                        'index',
                        ['name' => $indexItem['name']],
                        static function (XMLArray $XMLArray) use ($indexItem): void {
                            foreach ($indexItem['artist'] as $artist) {
                                $XMLArray->add(
                                    'artist',
                                    null,
                                    $artist,
                                );
                            }
                        },
                    );
                }
            },
        );
    }

    public function writeJson(array &$root): void
    {
        $root['artists'] = $this->artistList;
    }

    public function isBinaryResponder(): bool
    {
        return false;
    }
}
