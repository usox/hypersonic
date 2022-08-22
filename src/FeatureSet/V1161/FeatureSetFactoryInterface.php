<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

use Usox\HyperSonic\FeatureSet\FeatureSetInterface;

interface FeatureSetFactoryInterface extends FeatureSetInterface
{
    /**
     * @return array<string, callable(): Method\V1161MethodInterface>
     */
    public function getMethods(): array;

    public function createPingMethod(): Method\V1161MethodInterface;

    public function createGetArtistsMethod(): Method\V1161MethodInterface;

    public function createGetLicenseMethod(): Method\V1161MethodInterface;

    public function createGetCoverArtMethod(): Method\V1161MethodInterface;

    public function createGetArtistMethod(): Method\V1161MethodInterface;

    public function createGetGenresMethod(): Method\V1161MethodInterface;

    public function createGetMusicFoldersMethod(): Method\V1161MethodInterface;

    public function createGetAlbumMethod(): Method\V1161MethodInterface;
}
