<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Authentication;

use Usox\HyperSonic\Authentication\Exception\AbstractAuthenticationException;

interface AuthenticationProviderInterface
{
    /**
     * @throws AbstractAuthenticationException
     */
    public function authByToken(
        string $userName,
        string $token,
        string $salt,
    ): void;

    /**
     * @throws AbstractAuthenticationException
     */
    public function authByPassword(
        string $userName,
        string $password,
    ): void;
}
