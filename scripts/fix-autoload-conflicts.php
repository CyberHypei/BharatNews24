<?php

/**
 * Removes Laravel Pint and duplicate Flysystem paths from Composer autoload files
 * to fix "Ambiguous class resolution" warnings. Run after composer dump-autoload.
 */

$vendorDir = dirname(__DIR__) . '/vendor';
$composerDir = $vendorDir . '/composer';

$psr4File = $composerDir . '/autoload_psr4.php';
$staticFile = $composerDir . '/autoload_static.php';

if (!is_file($psr4File) || !is_file($staticFile)) {
    return;
}

// Fix autoload_psr4.php: keep only project paths for App, Database\Factories, Database\Seeders
$content = file_get_contents($psr4File);
$content = str_replace(
    "array(\$baseDir . '/database/seeders', \$vendorDir . '/laravel/pint/database/seeders')",
    "array(\$baseDir . '/database/seeders')",
    $content
);
$content = str_replace(
    "array(\$baseDir . '/database/factories', \$vendorDir . '/laravel/pint/database/factories')",
    "array(\$baseDir . '/database/factories')",
    $content
);
$content = str_replace(
    "array(\$baseDir . '/app', \$vendorDir . '/laravel/pint/app')",
    "array(\$baseDir . '/app')",
    $content
);
file_put_contents($psr4File, $content);

// Fix autoload_static.php: remove Pint from PSR-4 prefixDirsPsr4 and from classmap
$content = file_get_contents($staticFile);

// Remove Pint path from Database\\Seeders\\, Database\\Factories\\, App\\ (PSR-4 prefixDirsPsr4)
$content = str_replace(
    "1 => __DIR__ . '/..' . '/laravel/pint/database/seeders',\n        ",
    '',
    $content
);
$content = str_replace(
    "1 => __DIR__ . '/..' . '/laravel/pint/database/factories',\n        ",
    '',
    $content
);
$content = str_replace(
    "1 => __DIR__ . '/..' . '/laravel/pint/app',\n        ",
    '',
    $content
);

// Remove classmap entries that point to laravel/pint (App namespace from Pint only)
$lines = explode("\n", $content);
$filtered = [];
$skipNext = false;
foreach ($lines as $line) {
    if (preg_match("/^        'App\\\\[^']*' => __DIR__ \. '\/\.\.' \. '\/laravel\/pint\//", $line)) {
        continue;
    }
    $filtered[] = $line;
}
file_put_contents($staticFile, implode("\n", $filtered));
