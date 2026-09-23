<?php

namespace anovsiradj\skit\helpers;

use ErrorException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

/**
 * Backup/restore direktori (zip LZMA) & MySQL (mysqldump/mysql, redirect file).
 * Framework-agnostic: semua lokasi & kredensial lewat argumen.
 * Konvensi debug: 'locate' | 'command' | 'status' | 'result' | 'count' => kembalikan info, bukan jalan penuh.
 *
 * origin: C:\works\legacy\riung\riung_medsos_web\vendor\jeemce\laravel\helpers\BackupHelper.php (kerja4), C:\works\legacy\ditjen-migas-php\app\Http\backend\BackupController.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-23
 */
abstract class BackupHelper
{
    /**
     * zip satu direktori menjadi $targetFile (LZMA bila didukung).
     */
    public static function zipDir(string $sourceDir, string $targetFile, ?string $debug = null)
    {
        FileHelper::createDirectory(dirname($targetFile));

        if ($debug === 'locate') {
            return ['source' => $sourceDir, 'target' => $targetFile];
        }

        if (!extension_loaded('zip')) {
            throw new ErrorException('zip tidak tersedia!');
        }

        $zip = new ZipArchive();
        $status = $zip->open($targetFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($status !== true) {
            throw new ErrorException('zip tidak bisa dibuka!');
        }

        $sourceReal = realpath($sourceDir);
        if ($sourceReal === false || !is_dir($sourceReal)) {
            $zip->close();
            throw new ErrorException("source tidak ada: {$sourceDir}");
        }

        $count = 0;
        $rdi = new RecursiveDirectoryIterator($sourceReal, RecursiveDirectoryIterator::SKIP_DOTS);
        $rii = new RecursiveIteratorIterator($rdi, RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($rii as $file) {
            /** @var \SplFileInfo $file */
            if ($file->isDir() || $file->isLink()) {
                continue;
            }
            $absname = $file->getRealPath();
            $relname = ltrim(str_replace($sourceReal, '', (string) $absname), '/\\');

            $zip->addFile($absname, $relname);
            $zip->setCompressionName($relname, ZipArchive::CM_LZMA);
            $count++;
        }

        if ($debug === 'count') {
            $zip->close();
            return $count;
        }

        $status = $zip->close();
        if ($debug === 'status') {
            return $status;
        }
        if ($status !== true) {
            throw new ErrorException('zip tidak bisa disimpan!');
        }

        return $targetFile;
    }

    /**
     * ekstrak zip ke direktori target.
     */
    public static function unzipTo(string $zipFile, string $targetDir, ?string $debug = null)
    {
        $targetDir = rtrim($targetDir, '/\\');
        FileHelper::createDirectory($targetDir);

        if (!file_exists($zipFile) || !is_readable($zipFile)) {
            throw new ErrorException("file tidak tersedia: {$zipFile}");
        }
        if ($debug === 'locate') {
            return ['source' => $zipFile, 'target' => $targetDir];
        }
        if (!extension_loaded('zip')) {
            throw new ErrorException('zip tidak tersedia!');
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CHECKCONS) !== true) {
            throw new ErrorException('zip tidak bisa dibuka!');
        }

        $result = $zip->extractTo($targetDir);
        $status = $zip->close();

        return $debug === 'result' ? $result : $status;
    }

    /**
     * mysqldump --single-transaction ke file ($opts['target']).
     *
     * opts: binary:host, port, user, password, database, target, ignoreTables[], singleTransaction (true)
     */
    public static function mysqlDump(array $opts, ?string $debug = null)
    {
        $opts = array_merge([
            'binary' => 'mysqldump',
            'host' => '127.0.0.1',
            'port' => '3306',
            'user' => 'root',
            'password' => '',
            'database' => '',
            'target' => null,
            'ignoreTables' => [],
            'singleTransaction' => true,
        ], $opts);

        if (empty($opts['database'])) {
            throw new ErrorException('database kosong!');
        }

        $ignore = array_map(
            fn ($t) => '--ignore-table=' . escapeshellarg($opts['database'] . '.' . $t),
            (array) $opts['ignoreTables']
        );

        $command = implode(' ', array_filter([
            escapeshellarg($opts['binary']),
            sprintf('--host=%s', $opts['host']),
            sprintf('--port=%s', $opts['port']),
            sprintf('--user=%s', escapeshellarg($opts['user'])),
            $opts['password'] !== '' ? sprintf('--password=%s', escapeshellarg($opts['password'])) : null,
            $opts['singleTransaction'] ? '--single-transaction' : null,
            '--skip-lock-tables',
            '--quick',
            implode(' ', $ignore),
            escapeshellarg($opts['database']),
        ]));

        if ($debug === 'locate') {
            return ['database' => $opts['database'], 'target' => $opts['target'], 'binary' => $opts['binary']];
        }
        if ($debug === 'command') {
            return $command;
        }

        if (empty($opts['target'])) {
            throw new ErrorException('target kosong!');
        }
        FileHelper::createDirectory(dirname($opts['target']));

        $full = $command . ' > ' . escapeshellarg($opts['target']) . ' 2>&1';
        exec($full, $result, $status);

        if ($debug === 'status') {
            return $status;
        }
        if ($debug === 'result') {
            return $result;
        }
        if ($status !== 0) {
            throw new ErrorException('mysqldump gagal: ' . implode(PHP_EOL, (array) $result));
        }

        return $opts['target'];
    }

    /**
     * import file SQL ke MySQL (`mysql < file`).
     */
    public static function mysqlImport(array $opts, ?string $debug = null)
    {
        $opts = array_merge([
            'binary' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'user' => 'root',
            'password' => '',
            'database' => '',
            'file' => null,
        ], $opts);

        if (empty($opts['database']) || empty($opts['file'])) {
            throw new ErrorException('database/file kosong!');
        }
        if (!file_exists($opts['file']) || !is_readable($opts['file'])) {
            throw new ErrorException("file tidak tersedia: {$opts['file']}");
        }

        $command = implode(' ', array_filter([
            escapeshellarg($opts['binary']),
            sprintf('--host=%s', $opts['host']),
            sprintf('--port=%s', $opts['port']),
            sprintf('--user=%s', escapeshellarg($opts['user'])),
            $opts['password'] !== '' ? sprintf('--password=%s', escapeshellarg($opts['password'])) : null,
            escapeshellarg($opts['database']),
        ])) . ' < ' . escapeshellarg($opts['file']) . ' 2>&1';

        if ($debug === 'locate') {
            return ['database' => $opts['database'], 'file' => $opts['file'], 'binary' => $opts['binary']];
        }
        if ($debug === 'command') {
            return $command;
        }

        exec($command, $result, $status);

        if ($debug === 'status') {
            return $status;
        }
        if ($debug === 'result') {
            return $result;
        }
        if ($status !== 0) {
            throw new ErrorException('mysql import gagal: ' . implode(PHP_EOL, (array) $result));
        }

        return true;
    }
}
