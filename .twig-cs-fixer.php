<?php

declare(strict_types=1);

use TwigCsFixer\Config\Config;
use TwigCsFixer\File\Finder;
use TwigCsFixer\Ruleset\Ruleset;
use TwigCsFixer\Standard\TwigCsFixer;

$paths = [];
foreach ([__DIR__ . '/src', __DIR__ . '/templates'] as $path) {
    if (is_dir($path)) {
        $paths[] = $path;
    }
}

$finder = new Finder();
foreach ($paths as $path) {
    $finder->in($path);
}
$finder->exclude(['vendor', 'var', 'node_modules', 'coverage', 'demo']);

$ruleset = new Ruleset();
$ruleset->addStandard(new TwigCsFixer());

$config = new Config();
$config->setRuleset($ruleset);
$config->setFinder($finder);

return $config;
