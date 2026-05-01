<?php

/**
 * Tujuan: Lightweight test runner CLI.
 * Cara pakai: php tests/run.php [--filter <pattern>] [--list]
 * Dependency: tests/bootstrap.php, tests/assert.php
 */

if (php_sapi_name() !== 'cli') {
    die("Must be run from CLI");
}

require_once __DIR__ . '/bootstrap.php';

$options = getopt('', ['filter:', 'list']);
$filter = $options['filter'] ?? null;
$listOnly = isset($options['list']);

// Discover tests
$dir = new RecursiveDirectoryIterator(__DIR__ . '/src');
$iterator = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($iterator, '/^.+Test\.php$/i', RecursiveRegexIterator::GET_MATCH);

$testFiles = [];
foreach ($files as $file) {
    $path = $file[0];
    if ($filter && stripos($path, $filter) === false) {
        continue;
    }
    $testFiles[] = $path;
}

if ($listOnly) {
    echo "Test files found:\n";
    foreach ($testFiles as $file) {
        echo " - " . str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file) . "\n";
    }
    exit(0);
}

// Reset results
global $__test_results;
$__test_results = ['passed' => 0, 'failed' => 0, 'skipped' => 0, 'errors' => []];

echo "Running Tests...\n\n";

foreach ($testFiles as $file) {
    echo "-> " . str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file) . "\n";
    // Each test file is expected to call test_run() internally
    require_once $file;
    echo "\n";
}

echo "==============================\n";
echo "Summary:\n";
echo "Passed: {$__test_results['passed']}\n";
echo "Failed: {$__test_results['failed']}\n";
echo "Skipped: {$__test_results['skipped']}\n";
echo "==============================\n";

if ($__test_results['failed'] > 0) {
    exit(1);
}
exit(0);
