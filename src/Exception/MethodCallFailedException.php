<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Exception;

use Throwable;

final class MethodCallFailedException extends SubSonicApiException
{
    public function __construct(ErrorCodeEnum $errorCode, ?Throwable $previous = null)
    {
        parent::__construct('Method call failed', $errorCode->value, $previous);
    }
}
