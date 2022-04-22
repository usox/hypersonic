<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Exception;

enum ErrorCodeEnum: int
{
    case GENERIC_ERROR = 0;
    case MISSING_PARAMETER = 10;
    case CLIENT_VERSION_INCOMPATIBLE = 20;
    case SERVER_VERSION_INCOMPATIBLE = 30;
    case WRONG_USERNAME_OR_PASSWORD = 40;
    case TOKEN_AUTH_NOT_SUPPORTED = 41;
    case USER_NOT_AUTHORIZED = 50;
    case TRIAL_PERIOD_OVER = 60;
    case NOT_FOUND = 70;
}
