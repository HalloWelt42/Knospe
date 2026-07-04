<?php

declare(strict_types=1);

/*
 * Codestil nach PSR-12 (plus ein paar sinnvolle Ergaenzungen).
 * Pruefen:   composer lint
 * Anwenden:  composer lint:fix
 * Lern mehr: docs/09-entwicklung/01-code-style-psr-12.md
 */

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'declare_strict_types' => true,
        'single_quote' => true,
    ])
    ->setFinder($finder);
