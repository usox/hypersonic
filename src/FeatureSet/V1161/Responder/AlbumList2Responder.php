<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Traversable;
use Usox\HyperSonic\Response\FormattedResponderInterface;

final readonly class AlbumList2Responder implements FormattedResponderInterface
{
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
    public function __construct(
        private Traversable $albumList,
    ) {
    }

    public function writeXml(XMLArray $XMLArray): void
    {
        $XMLArray->startLoop(
            'albumList2',
            [],
            function (XMLArray $XMLArray): void {
                foreach ($this->albumList as $album) {
                    $XMLArray->add(
                        'album',
                        null,
                        $album,
                    );
                }
            },
        );
    }

    public function writeJson(array &$root): void
    {
        $root['albumList2'] = ['album' => iterator_to_array($this->albumList)];
    }

    public function isBinaryResponder(): bool
    {
        return false;
    }
}
