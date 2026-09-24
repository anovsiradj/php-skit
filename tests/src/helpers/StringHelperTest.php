<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\StringHelper + FileHelper + SignatureHelper.
 */
use anovsiradj\skit\helpers\FileHelper;
use anovsiradj\skit\helpers\SignatureHelper;
use anovsiradj\skit\helpers\StringHelper;
use anovsiradj\skit\tests\Assert;
use anovsiradj\skit\tests\Runner;

Runner::getInstance()->runTest('StringHelper - slug/limit/alphaNumeric', function () {
    Assert::equals('halo-dunia', StringHelper::slug('  Halo, Dunia!! '));
    Assert::equals('ma ...', StringHelper::limitText('ma ka nga', 4));
    Assert::equals('ma ka...', StringHelper::limitWords('ma ka nga pa', 2));
    Assert::equals('ab12  -', StringHelper::alphaNumeric('<b>ab12</b> &<> -'));
});

Runner::getInstance()->runTest('StringHelper - before/after', function () {
    Assert::equals('a', StringHelper::before('a.b.c', '.'));
    Assert::equals('b.c', StringHelper::after('a.b.c', '.'));
    Assert::equals('b', StringHelper::before(StringHelper::after('a.b.c', '.'), '.'));
    Assert::equals('abc', StringHelper::before('abc', '.'));
    Assert::equals('abc', StringHelper::after('abc', '.'));
    Assert::equals('abc', StringHelper::before('abc', ''));
    Assert::equals('http', StringHelper::before('http://x/y', '://'));
});

Runner::getInstance()->runTest('FileHelper - create/uniqueName', function () {
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'skit_fh_' . getmypid();
    FileHelper::createDirectory($dir);
    try {
        Assert::true(FileHelper::create("$dir/a.txt", 'isi'));
        Assert::equals('z.txt', FileHelper::uniqueName($dir, 'z.txt'));
        Assert::equals('a_2.txt', FileHelper::uniqueName($dir, 'a.txt'));
        Assert::equals('b_2.dat', FileHelper::uniqueName($dir, 'b_2.dat', fn ($p) => false));
    } finally {
        FileHelper::deleteFolder($dir, false);
    }
    Assert::false(is_dir($dir));
});

Runner::getInstance()->runTest('FileHelper - deleteFolder keepRoot/recursive', function () {
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'skit_fh2_' . getmypid();
    FileHelper::createDirectory("$dir/deeper/deepest");
    try {
        file_put_contents("$dir/f1.txt", 'x');
        file_put_contents("$dir/deeper/f2.txt", 'x');
        file_put_contents("$dir/deeper/deepest/f3.txt", 'x');

        FileHelper::deleteFolder($dir, true);
        Assert::true(is_dir($dir));
        Assert::false(is_file("$dir/f1.txt"));
        Assert::false(is_file("$dir/deeper/deepest/f3.txt"));
        Assert::false(is_dir("$dir/deeper"));
    } finally {
        FileHelper::deleteFolder($dir, false);
    }
    Assert::false(is_dir($dir));
});

Runner::getInstance()->runTest('SignatureHelper - generateManifest', function () {
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'skit_sig_' . getmypid();
    FileHelper::createDirectory("$dir/sub");
    try {
        file_put_contents("$dir/a.txt", 'hello');
        file_put_contents("$dir/sub/b.txt", 'world');

        $manifest = SignatureHelper::generateManifest($dir);
        Assert::equals(2, $manifest['total_files']);
        Assert::equals('sha256', $manifest['algorithm']);
        $paths = array_column($manifest['files'], 'path');
        sort($paths);
        Assert::equals(['a.txt', 'sub' . DIRECTORY_SEPARATOR . 'b.txt'], $paths);
        $byPath = array_column($manifest['files'], 'hash', 'path');
        Assert::equals(hash('sha256', 'hello'), $byPath['a.txt']);
    } finally {
        FileHelper::deleteFolder($dir, false);
    }
});
