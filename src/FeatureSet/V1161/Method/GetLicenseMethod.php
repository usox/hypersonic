<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\FeatureSet\V1161\Contract\LicenseDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

/**
 * Retrieves and transforms data related to the license state of the server
 *
 * This class covers the `getLicense.view` method
 */
final readonly class GetLicenseMethod implements V1161MethodInterface
{
    public function __construct(
        private ResponderFactoryInterface $responderFactory,
    ) {
    }

    /**
     * @param array<string, scalar> $queryParams
     * @param array<string, scalar> $args
     */
    public function __invoke(
        LicenseDataProviderInterface $licenseDataProvider,
        array $queryParams,
        array $args,
    ): ResponderInterface {
        return $this->responderFactory->createLicenseResponder([
            'valid' => $licenseDataProvider->isValid() ? 'true' : 'false',
            'email' => $licenseDataProvider->getEmailAddress(),
            'licenseExpires' => $licenseDataProvider->getExpiryDate()->format(DATE_ATOM),
        ]);
    }
}
