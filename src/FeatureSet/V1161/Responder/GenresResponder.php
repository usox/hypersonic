<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Usox\HyperSonic\Response\FormattedResponderInterface;

final readonly class GenresResponder implements FormattedResponderInterface
{
    /**
     * @param array<array{
     *  value: string,
     *  albumCount: int,
     *  songCount: int
     * }> $genres
     */
    public function __construct(
        private array $genres,
    ) {
    }

    public function writeXml(XMLArray $XMLArray): void
    {
        $XMLArray->startLoop(
            'genres',
            [],
            function (XMLArray $XMLArray): void {
                foreach ($this->genres as $genre) {
                    $XMLArray->add(
                        'genre',
                        htmlspecialchars($genre['value']),
                        [
                            'albumCount' => $genre['albumCount'],
                            'songCount' => $genre['songCount'],
                        ],
                    );
                }
            },
        );
    }

    public function writeJson(array &$root): void
    {
        $root['genres'] = ['genre' => $this->genres];
    }

    public function isBinaryResponder(): bool
    {
        return false;
    }
}
