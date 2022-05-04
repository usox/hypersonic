<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\Exception\ErrorCodeEnum;
use Usox\HyperSonic\Exception\MethodCallFailedException;
use Usox\HyperSonic\Exception\SubSonicApiException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\PingDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

/**
 * Retrieves and transforms data related to the servers state
 *
 * This class covers the `ping.view` method
 */
final class PingMethod implements V1161MethodInterface
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
        PingDataProviderInterface $dataProvider,
        array $queryParams,
        array $args
    ): ResponderInterface {
        if (!$dataProvider->isOk()) {
            throw new MethodCallFailedException(ErrorCodeEnum::GENERIC_ERROR);
        }

        return $this->responderFactory->createPingResponder();
    }
}
