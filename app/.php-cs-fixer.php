<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src/Modules/Player/Application/Document',
        __DIR__ . '/src/Modules/Player/Domain/Document',
        __DIR__ . '/src/Modules/Player/Infrastructure/Persistence',
        __DIR__ . '/src/Modules/Player/Presentation/Http/Document',
        __DIR__ . '/tests/Unit/Modules/Player/Document',
        __DIR__ . '/tests/Integration/Modules/Player/Document',
        __DIR__ . '/tests/Functional/Modules/Player/Document',
    ])
    ->files()
    ->name('*.php')
    ->notPath('PlayerRepository.php')
    ->notPath('PlayerImportJobRepository.php')
    ->notPath('PlayerGuardianRepository.php');

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS2.0' => true,
        'declare_strict_types' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'single_line_throw' => false,
    ])
    ->setFinder($finder);
