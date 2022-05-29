<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\Exception\SubSonicApiException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\GenreListDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

/**
 * Retrieves the list of available genres
 *
 * This class covers the `getGenres.view` method
 */
final class GetGenresMethod implements V1161MethodInterface
{
    public function __construct(
        private readonly ResponderFactoryInterface $responderFactory,
    ) {
    }

    /**
     * @param array<string, scalar> $queryParams
     * @param array<string, scalar> $args
     *
     * @throws SubSonicApiException
     */
    public function __invoke(
        GenreListDataProviderInterface $dataProvider,
        array $queryParams,
        array $args
    ): ResponderInterface {
        return $this->responderFactory->createGenresResponder(
            iterator_to_array($dataProvider->getGenres())
        );
    }
}
