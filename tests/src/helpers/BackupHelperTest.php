<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\BackupHelper
 */
use anovsiradj\skit\helpers\BackupHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('BackupHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\BackupHelper'));
});

Runner::getInstance()->runTest('BackupHelper - zipDir/unzipTo roundtrip', function () {
    if (!extension_loaded('zip')) {
        Assert::skip('ext-zip tidak tersedia');
    }

    $base = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'skit_backup_' . getmypid();
    $src = $base . DIRECTORY_SEPARATOR . 'src';
    mkdir($src . DIRECTORY_SEPARATOR . 'sub', 0755, true);
    file_put_contents($src . DIRECTORY_SEPARATOR . 'a.txt', 'isi-a');
    file_put_contents($src . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'b.txt', 'isi-b');

    $zip = $base . DIRECTORY_SEPARATOR . 'backup.zip';
    Assert::equals($zip, BackupHelper::zipDir($src, $zip));
    Assert::equals(2, BackupHelper::zipDir($src, $zip, 'count'));

    $dst = $base . DIRECTORY_SEPARATOR . 'dst';
    Assert::true(BackupHelper::unzipTo($zip, $dst));
    Assert::equals('isi-a', file_get_contents($dst . DIRECTORY_SEPARATOR . 'a.txt'));
    Assert::equals('isi-b', file_get_contents($dst . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'b.txt'));

    array_map('unlink', glob($dst . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . '*'));
    rmdir($dst . DIRECTORY_SEPARATOR . 'sub');
    array_map('unlink', glob($dst . DIRECTORY_SEPARATOR . '*'));
    rmdir($dst);
    unlink($zip);
    array_map('unlink', glob($src . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . '*'));
    rmdir($src . DIRECTORY_SEPARATOR . 'sub');
    array_map('unlink', glob($src . DIRECTORY_SEPARATOR . '*'));
    rmdir($src);
    rmdir($base);
});

Runner::getInstance()->runTest('BackupHelper - mysqlDump command preview', function () {
    $cmd = BackupHelper::mysqlDump(['database' => 'db_skit', 'ignoreTables' => ['logs']], 'command');
    Assert::true(is_string($cmd));
    Assert::true(strpos($cmd, '--ignore-table=' . escapeshellarg('db_skit.logs')) !== false);
});
