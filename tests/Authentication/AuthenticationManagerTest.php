<?php

declare(strict_types=1);

namespace Usox\HyperSonic\Authentication;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Psr\Http\Message\ServerRequestInterface;
use Usox\HyperSonic\Authentication\Exception\AuthenticationParamsMissingException;
use Usox\HyperSonic\Authentication\Exception\UsernameMissingException;

class AuthenticationManagerTest extends MockeryTestCase
{
    private MockInterface $authenticationProvider;

    private AuthenticationManager $subject;

    protected function setUp(): void
    {
        $this->authenticationProvider = Mockery::mock(AuthenticationProviderInterface::class);

        $this->subject = new AuthenticationManager(
            $this->authenticationProvider,
        );
    }

    public function testAuthThrowsExceptionIfUsernameIsMissing(): void
    {
        $this->expectException(UsernameMissingException::class);

        $request = Mockery::mock(ServerRequestInterface::class);

        $request->shouldReceive('getQueryParams')
            ->withNoArgs()
            ->once()
            ->andReturn([]);

        $this->subject->auth($request);
    }

    public function testAuthPerformsTokenAuth(): void
    {
        $userName = 'some-name';
        $token = 'some-token';
        $salt = 'himalaya';

        $request = Mockery::mock(ServerRequestInterface::class);

        $request->shouldReceive('getQueryParams')
            ->withNoArgs()
            ->once()
            ->andReturn([
                'u' => $userName,
                't' => $token,
                's' => $salt,
            ]);

        $this->authenticationProvider->shouldReceive('authByToken')
            ->with($userName, $token, $salt)
            ->once();

        $this->subject->auth($request);
    }

    public function testAuthPerformsPasswordAuthWithEncodedPassword(): void
    {
        $userName = 'some-name';
        $password = 'some-password';
        $passwordEncoded = sprintf('enc:%s', bin2hex($password));

        $request = Mockery::mock(ServerRequestInterface::class);

        $request->shouldReceive('getQueryParams')
            ->withNoArgs()
            ->once()
            ->andReturn([
                'u' => $userName,
                'p' => $passwordEncoded,
            ]);

        $this->authenticationProvider->shouldReceive('authByPassword')
            ->with($userName, $password)
            ->once();

        $this->subject->auth($request);
    }

    public function testAuthThrowsExceptionIfNoAuthMethodApplies(): void
    {
        $this->expectException(AuthenticationParamsMissingException::class);

        $userName = 'some-name';

        $request = Mockery::mock(ServerRequestInterface::class);

        $request->shouldReceive('getQueryParams')
            ->withNoArgs()
            ->once()
            ->andReturn([
                'u' => $userName,
            ]);

        $this->subject->auth($request);
    }
}
