<?php

/**
 * HowTo & Test: anovsiradj\skit\spreadsheet\SpreadHelper
 * 
 * Usage:
 * ```php
 * use anovsiradj\skit\spreadsheet\SpreadHelper;
 * // SpreadHelper::sheetNames('file.xlsx');
 * ```
 */

use anovsiradj\skit\spreadsheet\SpreadHelper;

test_run('SpreadHelper - class exists', function () {
    assert_true(class_exists('anovsiradj\skit\spreadsheet\SpreadHelper'));
});
