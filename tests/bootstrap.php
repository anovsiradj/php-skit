<?php

/**
 * Tujuan: Bootstrap environment untuk pengujian.
 * Cara pakai: di-require otomatis oleh runner (tests/run.php).
 * Dependency: composer autoload.
 */

// Pastikan error reporting maksimal
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Autoload composer
require_once __DIR__ . '/../vendor/autoload.php';

// Load assertion helpers
require_once __DIR__ . '/assert.php';

// Jika ada env, load
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}
