<?php

/**
 * HowTo & Test: src/functs/error.php
 * 
 * Usage:
 * ```php
 * require_once 'src/functs/error.php';
 * functErrorHandleDefault('something went wrong');
 * ```
 */

use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('functs/error.php - check function availability', function () {
    require_once __DIR__ . '/../../../src/functs/error.php';
    Assert::true(function_exists('functErrorHandleDefault'));
});
