<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Traversable;
use Usox\HyperSonic\Response\FormattedResponderInterface;

final class MusicFoldersResponder implements FormattedResponderInterface
{
    /**
     * @param Traversable<array{
     *  name: string,
     *  id: string
     * }> $musicFolders
     */
    public function __construct(
        private readonly Traversable $musicFolders
    ) {
    }

    public function writeXml(XMLArray $XMLArray): void
    {
        $XMLArray->startLoop(
            'musicFolders',
            [],
            function (XMLArray $XMLArray): void {
                foreach ($this->musicFolders as $musicFolder) {
                    $XMLArray->add(
                        'musicFolder',
                        '',
                        [
                            'name' => htmlspecialchars($musicFolder['name']),
                            'id' => $musicFolder['id'],
                        ]
                    );
                }
            }
        );
    }

    public function writeJson(array &$root): void
    {
        $root['musicFolders'] = ['musicFolder' => iterator_to_array($this->musicFolders)];
    }

    public function isBinaryResponder(): bool
    {
        return false;
    }
}
