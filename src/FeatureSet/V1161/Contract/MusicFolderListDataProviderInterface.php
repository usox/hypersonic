<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

use Traversable;

interface MusicFolderListDataProviderInterface
{
    /**
     * @return Traversable<array{name: string, id: string}>
     */
    public function getMusicFolders(): Traversable;
}
