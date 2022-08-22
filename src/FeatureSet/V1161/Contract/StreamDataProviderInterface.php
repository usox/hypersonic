<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Contract;

use Psr\Http\Message\StreamInterface;

interface StreamDataProviderInterface extends V1161DataProviderInterface
{
    /**
     * By expecting a Psr StreamInterface, we let the data provider decide
     * which kind of stream it will use. This allows a range of different options,
     * including live transcoding
     *
     * @return null|array{
     *  contentType: string,
     *  length: int,
     *  stream: StreamInterface,
     * }
     */
    public function stream(
        string $songId,
        ?string $format,
        ?int $bitrate,
    ): ?array;
}
