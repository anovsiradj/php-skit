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
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('SpreadHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\spreadsheet\SpreadHelper'));
});
