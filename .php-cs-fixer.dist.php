<?php

declare(strict_types=1);

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

return (new PhpCsFixer\Config())
    ->setRules(
        [
            '@PSR12' => true,
            'array_syntax' => ['syntax' => 'short'],
            'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline', 'keep_multiple_spaces_after_comma' => false],
            'no_unused_imports' => true,
            'ordered_imports' => [
                'sort_algorithm' => 'alpha',
                'imports_order' => ['const', 'class', 'function'],
            ],
            'fully_qualified_strict_types' => [
                'import_symbols' => true,
            ],
            'trailing_comma_in_multiline' => [
                'after_heredoc' => true,
                'elements' => ['array_destructuring', 'arrays', 'parameters', 'arguments'],
            ],
        ],
    )
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(['src', 'tests'])
    );
