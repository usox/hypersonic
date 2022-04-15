<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use Psr\Http\Message\ResponseInterface;

interface ResponseWriterInterface
{
    public function write(ResponseInterface $response): ResponseInterface;

    public function writeError(
        ResponseInterface $response,
        int $errorCode,
        string $message = ''
    ): ResponseInterface;

    /**
     * @param array{
     *  ignoredArticles: string,
     *  index: iterable<array{
     *    name: string,
     *    artist: array<array{
     *      id: string,
     *      name: string,
     *      coverArt?: string,
     *      artistImageUrl?: string,
     *      albumCount: int,
     *      starred?: string
     *    }>
     *  }>
     * } $artistList
     */
    public function writeArtistList(array $artistList): ResponseWriterInterface;

    public function writeLicense(array $data): ResponseWriterInterface;
}
