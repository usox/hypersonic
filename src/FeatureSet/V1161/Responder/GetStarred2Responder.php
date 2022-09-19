<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Traversable;
use Usox\HyperSonic\Response\FormattedResponderInterface;

final class GetStarred2Responder implements FormattedResponderInterface
{
    /**
     * @param Traversable<array{
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
    public function __construct(
        private readonly Traversable $songs,
        private readonly Traversable $albums
    ) {
    }

    public function writeXml(XMLArray $XMLArray): void
    {
        $XMLArray->startLoop(
            'starred2',
            [],
            function (XMLArray $XMLArray): void {
                foreach ($this->albums as $album) {
                    $XMLArray->add(
                        'album',
                        null,
                        $album
                    );
                }

                foreach ($this->songs as $song) {
                    $XMLArray->add(
                        'song',
                        null,
                        $song
                    );
                }
            }
        );
    }

    public function writeJson(array &$root): void
    {
        $root['starred2'] = [
            'album' => iterator_to_array($this->albums),
            'song' => iterator_to_array($this->songs),
        ];
    }

    public function isBinaryResponder(): bool
    {
        return false;
    }
}
