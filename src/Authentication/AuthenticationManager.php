<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use Usox\HyperSonic\Authentication\Exception\AbstractAuthenticationException;
use Usox\HyperSonic\Authentication\Exception\AuthenticationParamsMissingException;
use Usox\HyperSonic\Authentication\Exception\UsernameMissingException;

/**
 * Manages the authentication of subsonic api requests
 */
final readonly class AuthenticationManager implements AuthenticationManagerInterface
{
    public function __construct(
        private AuthenticationProviderInterface $authenticationProvider,
    ) {
    }

    /**
     * Will throw an exception on auth fault, otherwise nothing will happen
     *
     * @throws AbstractAuthenticationException
     */
    public function auth(
        ServerRequestInterface $request,
    ): void {
        $queryParams = $request->getQueryParams();

        $userName = $queryParams['u'] ?? null;

        if ($userName === null) {
            throw new UsernameMissingException();
        }

        $token = $queryParams['t'] ?? null;
        $salt = $queryParams['s'] ?? null;

        // token auth? phew, thank god
        if ($token !== null && $salt !== null) {
            $this->authenticationProvider->authByToken(
                (string) $userName,
                (string) $token,
                (string) $salt,
            );

            return;
        }

        /** @var null|string $password */
        $password = $queryParams['p'] ?? null;

        // meh, plaintext or hex-encoded password
        if ($password !== null) {
            if (str_starts_with($password, 'enc:')) {
                $password = hex2bin(str_replace('enc:', '', $password));
            }

            $this->authenticationProvider->authByPassword(
                (string) $userName,
                (string) $password,
            );

            return;
        }

        // no auth method applies? throw exception
        throw new AuthenticationParamsMissingException();
    }
}
