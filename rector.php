<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
	->withPaths([
		__DIR__ . '/src',
		__DIR__ . '/tests',
	])
	->withRules([
		InlineConstructorDefaultToPropertyRector::class,
	])
	->withPhpSets(php81: true)
	->withPreparedSets(deadCode: true, codeQuality: true, codingStyle: true)
	->withSkip([
		FlipTypeControlToUseExclusiveTypeRector::class,
	]);
