<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\Exception\PingFailedException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\PingDataProviderInterface;
use Usox\HyperSonic\Response\ResponseWriterInterface;

final class PingMethod implements V1161MethodInterface
{
    public function __construct(
        private readonly PingDataProviderInterface $pingDataProvider
    ) {
    }

    /**
     * @param array<string, scalar> $args
     */
    public function __invoke(
        ResponseWriterInterface $responseWriter,
        array $args
    ): void {
        if (!$this->pingDataProvider->isOk()) {
            throw new PingFailedException();
        }
    }
}
