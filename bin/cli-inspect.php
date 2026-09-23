<?php

/**
 * cli-inspect.php — query READ-ONLY ke database via stdin/file, output JSON di stdout.
 *
 * ```cli
 * # Bash
 * echo "SELECT * FROM users" | php bin/cli-inspect.php
 * php bin/cli-inspect.php file=runtime/tmp/query.sql
 * # PowerShell
 * 'SELECT * FROM users' | php bin/cli-inspect.php
 * Get-Content runtime/tmp/query.sql | php bin/cli-inspect.php
 * ```
 *
 * Sumber SQL (prioritas): 1) argumen `file=` ; 2) stdin.
 * Hanya statement awal SELECT/SHOW/DESC/DESCRIBE/EXPLAIN/PRAGMA/WITH;
 * multi-statement (titik-koma di tengah) ditolak. DML/DDL otomatis gagal.
 * Koneksi dari env DB_* (lihat cli-connect.php).
 *
 * origin: C:\works\legacy\riung\riung_medsos_web\cli-inspect.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-23
 */

$arguments = require __DIR__ . '/../src/files/arguments.php';

$connectEchoExit = false;
require __DIR__ . '/cli-connect.php';

if (empty($pdo)) {
    fwrite(STDERR, 'No PDO Object' . PHP_EOL);
    exit(1);
}

$sql = null;
if (isset($arguments['file'])) {
    $tmp = (string) $arguments['file'];
    if (file_exists($tmp) && is_file($tmp) && is_readable($tmp)) {
        $sql = trim((string) file_get_contents($tmp));
    } else {
        fwrite(STDERR, 'File Not Found' . PHP_EOL);
        exit(1);
    }
}
if (empty($sql)) {
    $sql = stream_get_contents(STDIN) ?: null;
}
if ($sql === null || trim($sql) === '') {
    fwrite(STDERR, 'No SQL input' . PHP_EOL);
    exit(1);
}

$sql = trim($sql);
$sql = rtrim($sql, ';');
if (strpos($sql, ';') !== false) {
    fwrite(STDERR, 'Multi-statement ditolak' . PHP_EOL);
    exit(1);
}

$allowed = ['SELECT', 'SHOW', 'DESC', 'DESCRIBE', 'EXPLAIN', 'PRAGMA', 'WITH'];
$head = strtoupper(strtok(preg_replace('/^\s*\/\*.*?\*\/\s*/s', '', $sql), " \t\n\r"));
if (!in_array($head, $allowed, true)) {
    fwrite(STDERR, 'Hanya query read-only yang diizinkan: ' . implode(', ', $allowed) . PHP_EOL);
    exit(1);
}

try {
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_NUM);
    $cols = [];

    for ($i = 0; $i < $stmt->columnCount(); $i++) {
        $meta = $stmt->getColumnMeta($i);
        $cols[] = $meta['name'] ?? "col_$i";
    }

    $result = [
        'columns' => $cols,
        'rows' => $rows,
        'rowCount' => count($rows),
    ];

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
} catch (Exception $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}
