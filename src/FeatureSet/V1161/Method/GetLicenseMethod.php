<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\FeatureSet\V1161\Contract\LicenseDataProviderInterface;
use Usox\HyperSonic\Response\ResponseWriterInterface;

final class GetLicenseMethod implements V1161MethodInterface
{
    public function __construct(
        private LicenseDataProviderInterface $licenseDataProvider,
    ) {
    }

    /**
     * @param array<string, scalar> $args
     */
    public function __invoke(
        ResponseWriterInterface $responseWriter,
        array $args
    ): void {
        $responseWriter->writeLicense([
            'valid' => $this->licenseDataProvider->isValid() ? 'true' : 'false',
            'email' => $this->licenseDataProvider->getEmailAddress(),
            'licenseExpires' => $this->licenseDataProvider->getExpiryDate()->format(DATE_ATOM),
        ]);
    }
}
