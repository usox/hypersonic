<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use Psr\Http\Message\ResponseInterface;

final class JsonResponseWriter implements ResponseWriterInterface
{
    private array $root = [
        'status' => 'ok',
        'version' => '1.16.1',
    ];

    public function write(ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write(
            json_encode(['subsonic-response' => $this->root], JSON_PRETTY_PRINT)
        );

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function writeError(ResponseInterface $response, int $errorCode): ResponseInterface
    {
        $data = [
            'subsonic-response' => [
                'status' => 'failed',
                'version' => '1.16.1',
                'error' => [
                    'code' => $errorCode,
                ],
            ],
        ];

        $response->getBody()->write(
            json_encode($data, JSON_PRETTY_PRINT)
        );

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function writeLicense(array $data): ResponseWriterInterface
    {
        $this->root['license'] = $data;

        return $this;
    }

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
    public function writeArtistList(array $artistList): ResponseWriterInterface
    {
        $this->root['artists'] = $artistList;

        return $this;
    }
}
