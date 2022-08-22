<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\Exception\ErrorCodeEnum;
use Usox\HyperSonic\Exception\MethodCallFailedException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\StreamDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

/**
 * Streams the requested file and respects the optional stream config parameters
 *
 * This class covers the `stream.view` method
 */
final class StreamMethod implements V1161MethodInterface
{
    public function __construct(
        private readonly ResponderFactoryInterface $responderFactory,
    ) {
    }

    /**
     * Calls the stream provider with optional transcoding config
     *
     * If the transcoding config is missing, the stream provider will
     * decides if a transcoding will be performed and uses its default
     * settings
     *
     * @see http://www.subsonic.org/pages/api.jsp#stream
     *
     * @param array<string, scalar> $queryParams
     * @param array<string, scalar> $args
     *
     * @throws MethodCallFailedException
     */
    public function __invoke(
        StreamDataProviderInterface $streamDataProvider,
        array $queryParams,
        array $args
    ): ResponderInterface {
        $bitrate = (int) ($queryParams['maxBitRate'] ?? 0);
        if ($bitrate === 0) {
            $bitrate = null;
        }

        $format = (string) ($queryParams['format'] ?? '');
        if ($format === '') {
            $format = null;
        }

        $streamData = $streamDataProvider->stream(
            (string) ($queryParams['id'] ?? ''),
            $format,
            $bitrate
        );

        if ($streamData === null) {
            throw new MethodCallFailedException(
                ErrorCodeEnum::NOT_FOUND
            );
        }

        $estimateContentLength = (bool) ($queryParams['estimateContentLength'] ?? false);

        if ($estimateContentLength === true && $bitrate > 0) {
            $streamData['estimatedContentLength'] = $streamData['length'] * $bitrate * 1000 / 8;
        }

        return $this->responderFactory->createStreamResponder(
            $streamData,
        );
    }
}
