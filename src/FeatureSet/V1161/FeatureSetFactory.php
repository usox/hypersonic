<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161;

use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactory;

/**
 * Provides the  methods for all subsonic 1.16.1 features
 */
final class FeatureSetFactory implements FeatureSetFactoryInterface
{
    public function getVersion(): string
    {
        return '1.16.1';
    }

    /**
     * @return array<string, callable(): Method\V1161MethodInterface>
     */
    public function getMethods(): array
    {
        return [
            'ping.view' => static fn (): Method\V1161MethodInterface => new Method\PingMethod(
                new ResponderFactory()
            ),
            'getLicense.view' => static fn (): Method\V1161MethodInterface => new Method\GetLicenseMethod(
                new ResponderFactory()
            ),
            'getAlbum.view' => static fn (): Method\V1161MethodInterface => new Method\GetAlbumMethod(
                new ResponderFactory()
            ),
            'getAlbumList2.view' => static fn (): Method\V1161MethodInterface => new Method\GetAlbumList2Method(
                new ResponderFactory()
            ),
            'getArtists.view' => static fn (): Method\V1161MethodInterface => new Method\GetArtistsMethod(
                new ResponderFactory()
            ),
            'getCoverArt.view' => static fn (): Method\V1161MethodInterface => new Method\GetCoverArtMethod(
                new ResponderFactory()
            ),
            'getArtist.view' => static fn (): Method\V1161MethodInterface => new Method\GetArtistMethod(
                new ResponderFactory()
            ),
            'getGenres.view' => static fn (): Method\V1161MethodInterface => new Method\GetGenresMethod(
                new ResponderFactory()
            ),
            'getMusicFolders.view' => static fn (): Method\V1161MethodInterface => new Method\GetMusicFoldersMethod(
                new ResponderFactory()
            ),
            'stream.view' => static fn (): Method\V1161MethodInterface => new Method\StreamMethod(
                new ResponderFactory()
            ),
            'getStarred2.view' => static fn (): Method\V1161MethodInterface => new Method\GetStarred2Method(
                new ResponderFactory()
            ),
        ];
    }
}
