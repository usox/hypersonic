<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\Exception\PingFailedException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\PingDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;
use Usox\HyperSonic\Response\ResponseWriterInterface;

final class PingMethod implements V1161MethodInterface
{
    public function __construct(
        private readonly ResponderFactoryInterface $responderFactory,
    ) {
    }

    /**
     * @param array<string, scalar> $args
     */
    public function __invoke(
        ResponseWriterInterface $responseWriter,
        PingDataProviderInterface $dataProvider,
        array $args
    ): ResponderInterface {
        if (!$dataProvider->isOk()) {
            throw new PingFailedException();
        }

        return $this->responderFactory->createPingResponder();
    }
}
