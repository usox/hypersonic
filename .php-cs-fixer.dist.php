<?php

$finder = PhpCsFixer\Finder::create()
    ->in(['src', 'tests'])
;
$config = new PhpCsFixer\Config();
$config->setRules(
    [
        '@PSR12' => true,
        '@PHP80Migration' => true,
        'array_syntax' => ['syntax' => 'short'],
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline', 'keep_multiple_spaces_after_comma' => false],
    ]
)->setFinder($finder);

return $config;