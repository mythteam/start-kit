<?php

$config = Symfony\CS\Config\Config::create();
$config->fixers(array(
    '@PSR2',
    'header_comment',
    'short_array_syntax',
    'ordered_use',
    'php_unit_construct',
    'php_unit_strict',
    'phpdoc_order',
    // 'strict_param',
    'align_double_arrow' => false,
    'align_equals' => false,
    'concat_with_spaces',
    // 'concat_without_spaces' => false,
    'phpdoc_no_package' => false,
    'empty_return' => false,
));
$config->setUsingCache(true);
$config->finder(
    Symfony\CS\Finder\DefaultFinder::create()
        ->exclude(['vendor', 'tools', 'tests', 'crontab', 'views', 'runtime', 'web'])
        ->in(__DIR__)
);

return $config;
