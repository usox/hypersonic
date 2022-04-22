<?php

namespace Usox\HyperSonic\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use Usox\HyperSonic\Authentication\Exception\AbstractAuthenticationException;

interface AuthenticationManagerInterface
{
    /**
     * @throws AbstractAuthenticationException
     */
    public function auth(
        ServerRequestInterface $request,
    ): void;
}
