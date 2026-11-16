#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Validate translation YAML syntax and key parity across required locales (REQ-I18N-002 / REQ-MAKE-004).
 */
use Symfony\Component\Yaml\Yaml;

require dirname(__DIR__) . '/vendor/autoload.php';

$requiredLocales = ['en', 'es', 'it', 'fr', 'pt', 'de', 'nl'];
$domain          = 'NowoPasswordToggleBundle';
$dir             = dirname(__DIR__) . '/src/Resources/translations';

$catalogues = [];
foreach ($requiredLocales as $locale) {
    $file = sprintf('%s/%s.%s.yaml', $dir, $domain, $locale);
    if (!is_file($file)) {
        fwrite(STDERR, "Missing catalogue: {$file}\n");
        exit(1);
    }
    $parsed = Yaml::parseFile($file);
    if (!is_array($parsed)) {
        fwrite(STDERR, "Invalid YAML (not a map): {$file}\n");
        exit(1);
    }
    $catalogues[$locale] = array_keys($parsed);
    sort($catalogues[$locale]);
}

$reference = $catalogues['en'];
$ok        = true;
foreach ($requiredLocales as $locale) {
    if ($locale === 'en') {
        continue;
    }
    $missing = array_diff($reference, $catalogues[$locale]);
    $extra   = array_diff($catalogues[$locale], $reference);
    if ($missing !== [] || $extra !== []) {
        $ok = false;
        fwrite(STDERR, "Key parity failed for locale {$locale}\n");
        if ($missing !== []) {
            fwrite(STDERR, '  missing: ' . implode(', ', $missing) . "\n");
        }
        if ($extra !== []) {
            fwrite(STDERR, '  extra: ' . implode(', ', $extra) . "\n");
        }
    }
}

if (!$ok) {
    exit(1);
}

echo 'OK — ' . count($requiredLocales) . ' locales, ' . count($reference) . " keys (domain {$domain})\n";
