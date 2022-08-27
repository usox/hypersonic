<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use DateTime;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Usox\HyperSonic\FeatureSet\V1161\Contract\LicenseDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

class GetLicenseMethodTest extends MockeryTestCase
{
    private MockInterface $responderFactory;

    private GetLicenseMethod $subject;

    protected function setUp(): void
    {
        $this->responderFactory = Mockery::mock(ResponderFactoryInterface::class);

        $this->subject = new GetLicenseMethod(
            $this->responderFactory,
        );
    }

    public function testInvokeReturnsData(): void
    {
        $dataProvider = Mockery::mock(LicenseDataProviderInterface::class);
        $responder = Mockery::mock(ResponderInterface::class);

        $emailAddress = 'some-email';
        $date = new DateTime();

        $dataProvider->shouldReceive('isValid')
            ->withNoArgs()
            ->once()
            ->andReturnTrue();
        $dataProvider->shouldReceive('getEmailAddress')
            ->withNoArgs()
            ->once()
            ->andReturn($emailAddress);
        $dataProvider->shouldReceive('getExpiryDate')
            ->withNoArgs()
            ->once()
            ->andReturn($date);

        $this->responderFactory->shouldReceive('createLicenseResponder')
            ->with([
                'valid' => 'true',
                'email' => $emailAddress,
                'licenseExpires' => $date->format(DATE_ATOM),
            ])
            ->once()
            ->andReturn($responder);

        $this->assertSame(
            $responder,
            call_user_func($this->subject, $dataProvider, [], [])
        );
    }
}
