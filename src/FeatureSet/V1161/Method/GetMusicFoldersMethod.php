<?php

declare(strict_types=1);

namespace Usox\HyperSonic\FeatureSet\V1161\Method;

use Usox\HyperSonic\Exception\SubSonicApiException;
use Usox\HyperSonic\FeatureSet\V1161\Contract\MusicFolderListDataProviderInterface;
use Usox\HyperSonic\FeatureSet\V1161\Responder\ResponderFactoryInterface;
use Usox\HyperSonic\Response\ResponderInterface;

/**
 * Retrieves the list of available music folders
 *
 * This class covers the `getMusicFolders.view` method
 */
final readonly class GetMusicFoldersMethod implements V1161MethodInterface
{
    public function __construct(
        private ResponderFactoryInterface $responderFactory,
    ) {
    }

    /**
     * @param array<string, scalar> $queryParams
     * @param array<string, scalar> $args
     *
     * @throws SubSonicApiException
     */
    public function __invoke(
        MusicFolderListDataProviderInterface $dataProvider,
        array $queryParams,
        array $args,
    ): ResponderInterface {
        return $this->responderFactory->createMusicFoldersResponder(
            $dataProvider->getMusicFolders(),
        );
    }
}
