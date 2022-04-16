<?php

/*
 * CleverCall
 * (c) CleverIT - https://www.cleverit.nl/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

$header = <<<EOF
The Xross Entity Map
https://github.com/NMe84/xem

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->in(['src', 'tests'])
    ->files()
    ->name('*.php')
;

return (new \PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,

        'declare_strict_types' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'header_comment' => ['header' => $header, 'location' => 'after_open'],

        'blank_line_before_statement' => false,
        'mb_str_functions' => true,
        'ordered_imports' => true,
        'phpdoc_align' => false,
        'phpdoc_separation' => false,
        'phpdoc_var_without_name' => false,
    ])
    ->setFinder($finder)
;
