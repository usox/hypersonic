<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Response;

use AaronDDM\XMLBuilder\Writer\XMLWriterService;
use AaronDDM\XMLBuilder\XMLArray;
use AaronDDM\XMLBuilder\XMLBuilder;
use Psr\Http\Message\ResponseInterface;
use Usox\HyperSonic\Exception\ErrorCodeEnum;

final class XmlResponseWriter implements ResponseWriterInterface
{
    private XMLBuilder $XMLBuilder;

    private ?XMLArray $rootNode = null;

    public function __construct(
        private XMLWriterService $XMLWriterService,
    ) {
        $this->XMLBuilder = new XMLBuilder($this->XMLWriterService);
    }

    public function write(ResponseInterface $response): ResponseInterface
    {
        $this->getRootNode()->end();

        $response->getBody()->write(
            $this->XMLBuilder->getXML()
        );

        return $response->withHeader('Content-Type', 'application/xml');
    }

    public function writeError(ResponseInterface $response, ErrorCodeEnum $errorCode, string $message = ''): ResponseInterface
    {
        $this->XMLBuilder->createXMLArray()
            ->start(
                'subsonic-response',
                [
                    'xmlns' => 'http://subsonic.org/restapi',
                    'status' => 'failed',
                    'version' => '1.16.1',
                ]
            )
            ->add(
                'error',
                null,
                ['code' => $errorCode->value, 'message' => $message]
            )
            ->end();

        $response->getBody()->write(
            $this->XMLBuilder->getXML()
        );

        return $response->withHeader('Content-Type', 'application/xml');
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
        $this->getRootNode()->startLoop(
            'artists',
            ['ignoredArticles' => $artistList['ignoredArticles']],
            function (XMLArray $XMLArray) use ($artistList) {
                foreach ($artistList['index'] as $indexItem) {
                    $XMLArray->startLoop(
                        'index',
                        ['name' => $indexItem['name']],
                        function (XMLArray $XMLArray) use ($indexItem) {
                            foreach ($indexItem['artist'] as $artist) {
                                $XMLArray->add(
                                    'artist',
                                    null,
                                    $artist
                                );
                            }
                        }
                    );
                }
            }
        );

        return $this;
    }

    private function getRootNode(): XMLArray
    {
        if ($this->rootNode === null) {
            $this->rootNode = $this->XMLBuilder->createXMLArray()
                ->start(
                    'subsonic-response',
                    [
                        'xmlns' => 'http://subsonic.org/restapi',
                        'status' => 'ok',
                        'version' => '1.16.1',
                    ]
                );
        }
        return $this->rootNode;
    }

    public function writeLicense(array $data): ResponseWriterInterface
    {
        $this->getRootNode()->add(
            'license',
            null,
            $data
        );

        return $this;
    }
}
