<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\FeatureSet\V1161\Contract\GetCoverArtDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

/**
 * Retrieves and transforms data regarding artists- and album covers
 *
 * This class covers the `getCoverArt.view` method
 */
final class GetCoverArtMethod implements V1161MethodInterface
{
    public function __construct(
        private readonly ResponderFactoryInterface $responderFactory,
    ) {
    }

    /**
     * @param array<string, scalar> $queryParams
     * @param array<string, scalar> $args
     */
    public function __invoke(
        GetCoverArtDataProviderInterface $getCoverArtDataProvider,
        array $queryParams,
        array $args
    ): ResponderInterface {
        $art = $getCoverArtDataProvider->getArt(
            (string) ($queryParams['id'] ?? '')
        );

        return $this->responderFactory->createCoverArtResponder(
            $art['art'],
            $art['contentType'],
        );
    }
}
