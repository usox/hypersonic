<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Usox\HyperSonic\Response\FormattedResponderInterface;

final readonly class ArtistResponder implements FormattedResponderInterface
{
    /**
     * @param array{
     *  id: string,
     *  name: string,
     *  coverArt: string,
     *  albumCount: int,
     *  artistImageUrl: string,
     * } $artist
     * @param array<array{
     *  id: string,
     *  name: string,
     *  coverArt: string,
     *  songCount: int,
     *  created: string,
     *  duration: int,
     *  artist: string,
     *  artistId: string,
     *  year: string,
     *  genre: string,
     *  playCount: int
     * }> $albums
     */
    public function __construct(
        private array $artist,
        private array $albums,
    ) {
    }

    public function writeXml(XMLArray $XMLArray): void
    {
        $artistNode = $XMLArray->start(
            'artist',
            $this->artist,
        );

        foreach ($this->albums as $album) {
            $artistNode->add(
                'album',
                null,
                $album,
            );
        }

        $artistNode->end();
    }

    public function writeJson(array &$root): void
    {
        $root['artist'] = $this->artist;
        $root['artist']['album'] = $this->albums;
    }

    public function isBinaryResponder(): bool
    {
        return false;
    }
}
