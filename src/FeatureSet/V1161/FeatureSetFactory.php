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
            'ping.view' => fn (): Method\V1161MethodInterface => $this->createPingMethod(),
            'getLicense.view' => fn (): Method\V1161MethodInterface => $this->createGetLicenseMethod(),
            'getArtists.view' => fn (): Method\V1161MethodInterface => $this->createGetArtistsMethod(),
            'getCoverArt.view' => fn (): Method\V1161MethodInterface => $this->createGetCoverArtMethod(),
        ];
    }

    public function createPingMethod(): Method\V1161MethodInterface
    {
        return new Method\PingMethod(
            new ResponderFactory(),
        );
    }

    public function createGetArtistsMethod(): Method\V1161MethodInterface
    {
        return new Method\GetArtistsMethod(
            new ResponderFactory(),
        );
    }

    public function createGetLicenseMethod(): Method\V1161MethodInterface
    {
        return new Method\GetLicenseMethod(
            new ResponderFactory(),
        );
    }

    public function createGetCoverArtMethod(): Method\V1161MethodInterface
    {
        return new Method\GetCoverArtMethod(
            new ResponderFactory()
        );
    }
}
