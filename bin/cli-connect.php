<?php

/**
 * cli-connect.php — bootstrap koneksi PDO dari env DB_* (mysql/sqlite), bagian dari keluarga cli-*.
 *
 * ```cli
 * php bin/cli-connect.php            # healthcheck: cetak versi DB, exit 0/1
 * ```
 *
 * dipakai ulang:
 * ```php
 * $connectEchoExit = false;
 * require __DIR__ . '/cli-connect.php';   // tersedia $pdo
 * ```
 *
 * .env dibaca via symfony/dotenv bila terpasang (dari cwd); tanpa itu hanya env proses.
 *
 * origin: C:\works\legacy\riung\riung_medsos_web\cli-connect.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-23
 */

$autoloads = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php',
];
foreach ($autoloads as $autoload) {
    if (file_exists($autoload)) {
        require_once $autoload;
        break;
    }
}

// dipanggil langsung (bukan di-require): default = healthcheck echo versi DB
$connectEchoExit ??= count(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)) === 0;

if (class_exists('Dotenv\Dotenv')) {
    try {
        \Dotenv\Dotenv::createImmutable(getcwd() ?: __DIR__)->safeLoad();
    } catch (Throwable $e) {
    }
}

$env = fn ($k, $d = null) => $_ENV[$k] ?? $_SERVER[$k] ?? getenv($k) ?: $d;

$driver = strtolower((string) $env('DB_CONNECTION', 'mysql'));
$pdo = null;
$ok = false;

try {
    if ($driver === 'sqlite') {
        $path = (string) $env('DB_DATABASE', ':memory:');
        $pdo = new PDO('sqlite:' . $path);
    } else {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $env('DB_HOST', '127.0.0.1'),
            $env('DB_PORT', '3306'),
            $env('DB_DATABASE', ''),
            $env('DB_CHARSET', 'utf8')
        );
        $pdo = new PDO($dsn, (string) $env('DB_USERNAME', ''), (string) $env('DB_PASSWORD', ''));
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($connectEchoExit) {
        $sql = $driver === 'sqlite' ? 'SELECT sqlite_version() AS ver' : 'SELECT VERSION() AS ver';
        $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        echo $row['ver'] ?? '?', PHP_EOL;
    }
    $ok = true;
} catch (Exception $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    if ($connectEchoExit) {
        exit(1);
    }
}

if ($connectEchoExit) {
    exit($ok ? 0 : 1);
}
