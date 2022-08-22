<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Usox\HyperSonic\Response\FormattedResponderInterface;

final class AlbumResponder implements FormattedResponderInterface
{
    /**
     * @param array{
     *  id: string,
     *  name: string,
     *  coverArt: string,
     *  songCount: int,
     *  created: string,
     *  duration: int,
     *  artist: string,
     *  artistId: string,
     * } $album
     * @param array<array{
     *  id: string,
     *  isDir: bool,
     *  title: string,
     *  album: string,
     *  artist: string,
     *  track: int,
     *  coverArt: string,
     *  size: int,
     *  contentType: string,
     *  duration: int,
     *  created: string,
     *  albumId: string,
     *  artistId: string,
     *  playCount: int,
     * }> $songs
     */
    public function __construct(
        private readonly array $album,
        private readonly array $songs,
    ) {
    }

    public function writeXml(XMLArray $XMLArray): void
    {
        $albumNode = $XMLArray->start(
            'album',
            $this->album,
        );

        foreach ($this->songs as $song) {
            $albumNode->add(
                'song',
                null,
                $song
            );
        }

        $albumNode->end();
    }

    public function writeJson(array &$root): void
    {
        $root['album'] = $this->album;
        $root['album']['song'] = $this->songs;
    }

    public function isBinaryResponder(): bool
    {
        return false;
    }
}
