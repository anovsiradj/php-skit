<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\FileHelper
 */
use anovsiradj\skit\helpers\FileHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('FileHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\FileHelper'));
});

Runner::getInstance()->runTest('FileHelper - createDirectory/create/update', function () {
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'skit_fh_' . getmypid() . DIRECTORY_SEPARATOR . 'a/b';
    $file = $dir . DIRECTORY_SEPARATOR . 'f.txt';

    Assert::true(FileHelper::createDirectory($dir));
    Assert::true(is_dir($dir));
    Assert::true(FileHelper::create($file, 'satu'));
    Assert::equals('satu', file_get_contents($file));
    Assert::true(FileHelper::update($file, 'dua'));
    Assert::equals('dua', file_get_contents($file));
    Assert::false(FileHelper::create($file, null));

    unlink($file);
    rmdir($dir);
    rmdir(dirname($dir));
    rmdir(dirname(dirname($dir)));
    rmdir(dirname(dirname(dirname($dir))));
});

Runner::getInstance()->runTest('FileHelper - uniqueName', function () {
    $dir = sys_get_temp_dir();
    $used = [$dir . DIRECTORY_SEPARATOR . 'a.txt' => true, $dir . DIRECTORY_SEPARATOR . 'a_2.txt' => true];
    $existsFn = fn ($p) => isset($used[$p]);

    Assert::equals('a_3.txt', FileHelper::uniqueName($dir, 'a.txt', $existsFn));
    Assert::equals('b.txt', FileHelper::uniqueName($dir, 'b.txt', $existsFn));
    Assert::equals('c_2', FileHelper::uniqueName($dir, 'c', fn ($p) => $p === $dir . DIRECTORY_SEPARATOR . 'c'));
});
