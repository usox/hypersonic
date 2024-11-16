<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Responder;

use AaronDDM\XMLBuilder\XMLArray;
use Usox\HyperSonic\Response\FormattedResponderInterface;

final readonly class LicenseResponder implements FormattedResponderInterface
{
    /**
     * @param array{
     *  valid: string,
     *  email: string,
     *  licenseExpires: string
     * } $licenseData
     */
    public function __construct(
        private array $licenseData,
    ) {
    }

    public function writeXml(XMLArray $XMLArray): void
    {
        $XMLArray->add(
            'license',
            null,
            $this->licenseData,
        );
    }

    public function writeJson(array &$root): void
    {
        $root['license'] = $this->licenseData;
    }

    public function isBinaryResponder(): bool
    {
        return false;
    }
}
