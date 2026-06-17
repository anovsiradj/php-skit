<?php

/**
 * HowTo & Test: anovsiradj\skit\spreadsheet\FacadeHelper
 * 
 * Usage:
 * ```php
 * use anovsiradj\skit\spreadsheet\FacadeHelper;
 * $reader = FacadeHelper::reader(null, ['ext' => 'xlsx']);
 * ```
 */

use anovsiradj\skit\spreadsheet\FacadeHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('FacadeHelper - dependency missing behavior', function () {
    // If phpspreadsheet is loaded, skip this negative test
    if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        Assert::skip('phpoffice/phpspreadsheet is loaded, cannot test negative case');
    }

    try {
        FacadeHelper::reader();
        Assert::false(true, 'Should have thrown exception');
    } catch (\RuntimeException $e) {
        Assert::true(strpos($e->getMessage(), 'phpoffice/phpspreadsheet') !== false);
    }
});

Runner::getInstance()->runTest('FacadeHelper - reader init', function () {
    if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        Assert::skip('phpoffice/phpspreadsheet not loaded');
    }

    $reader = FacadeHelper::reader(null, ['ext' => 'csv']);
    Assert::true($reader instanceof \PhpOffice\PhpSpreadsheet\Reader\Csv);
});
