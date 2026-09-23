<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\CsvHelper
 */
use anovsiradj\skit\helpers\CsvHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('CsvHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\CsvHelper'));
});

Runner::getInstance()->runTest('CsvHelper - toArray', function () {
    $file = tempnam(sys_get_temp_dir(), 'skit_csv_');
    file_put_contents($file, "nama,jumlah,harga\napel,2,\"3,000\"\njeruk,3\n");

    $rows = CsvHelper::toArray($file);
    Assert::true(is_array($rows));
    Assert::equals(2, count($rows));
    Assert::equals('apel', $rows[0]['nama']);
    Assert::equals('3,000', $rows[0]['harga']);
    Assert::equals(null, $rows[1]['harga']);

    unlink($file);
});

Runner::getInstance()->runTest('CsvHelper - toArray unreadable', function () {
    Assert::false(CsvHelper::toArray(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'tidak_ada_skit_' . getmypid() . '.csv'));
});
