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

test_run('FacadeHelper - dependency missing behavior', function () {
    // If phpspreadsheet is loaded, skip this negative test
    if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        test_skip('phpoffice/phpspreadsheet is loaded, cannot test negative case');
    }

    try {
        FacadeHelper::reader();
        assert_false(true, 'Should have thrown exception');
    } catch (\RuntimeException $e) {
        assert_true(strpos($e->getMessage(), 'phpoffice/phpspreadsheet') !== false);
    }
});

test_run('FacadeHelper - reader init', function () {
    if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        test_skip('phpoffice/phpspreadsheet not loaded');
    }

    $reader = FacadeHelper::reader(null, ['ext' => 'csv']);
    assert_true($reader instanceof \PhpOffice\PhpSpreadsheet\Reader\Csv);
});
