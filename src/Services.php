<?php

declare(strict_types=1);

namespace Usox\HyperSonic;

use AaronDDM\XMLBuilder\Writer\XMLWriterService;
use DateTime;
use function DI\autowire;
use function DI\get;

return [
    HyperSonic::class => autowire()
        ->constructorParameter('featureSet', get(FeatureSet\V1161\FeatureSet1161::class)),
    FeatureSet\V1161\FeatureSet1161::class => autowire()
        ->constructorParameter(
            'methods',
            [
                'ping.view' => get(FeatureSet\V1161\Method\PingMethod::class),
                'getLicense.view' => get(FeatureSet\V1161\Method\GetLicenseMethod::class),
                'getArtists.view' => get(FeatureSet\V1161\Method\GetArtistsMethod::class),
            ]
        ),
    XMLWriterService::class => autowire(),

    Response\ResponseWriterFactoryInterface::class => autowire(Response\ResponseWriterFactory::class),
];
