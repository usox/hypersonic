<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use Usox\HyperSonic\Response\ResponderInterface;

final class ResponderFactory implements ResponderFactoryInterface
{
    /**
     * @param array{
     *  ignoredArticles: string,
     *  index: array<array{
     *    name: string,
     *    artist: array<array{
     *      id: string,
     *      name: string,
     *      coverArt: string,
     *      artistImageUrl: string,
     *      albumCount: int,
     *      starred?: string
     *    }>
     *  }>
     * } $artistList
     */
    public function createArtistsResponder(
        array $artistList
    ): ResponderInterface {
        return new ArtistsResponder(
            $artistList,
        );
    }

    /**
     * @param array{
     *  valid: string,
     *  email: string,
     *  licenseExpires: string
     * } $licenseData
     */
    public function createLicenseResponder(
        array $licenseData
    ): ResponderInterface {
        return new LicenseResponder(
            $licenseData,
        );
    }

    public function createPingResponder(): ResponderInterface
    {
        return new PingResponder();
    }
}
