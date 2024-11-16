<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Traversable;
use Usox\HyperSonic\Response\FormattedResponderInterface;

final readonly class GetRandomSongsResponder implements FormattedResponderInterface
{
    /**
     * @param Traversable<array{
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
     * }> $songs
     */
    public function __construct(
        private Traversable $songs,
    ) {
    }

    public function writeXml(XMLArray $XMLArray): void
    {
        $XMLArray->startLoop(
            'randomSongs',
            [],
            function (XMLArray $XMLArray): void {
                foreach ($this->songs as $song) {
                    $XMLArray->add(
                        'song',
                        null,
                        $song,
                    );
                }
            },
        );
    }

    public function writeJson(array &$root): void
    {
        $root['randomSongs'] = ['song' => iterator_to_array($this->songs)];
    }

    public function isBinaryResponder(): bool
    {
        return false;
    }
}
