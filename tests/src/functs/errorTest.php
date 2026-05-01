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

test_run('functs/error.php - check function availability', function () {
    require_once __DIR__ . '/../../../src/functs/error.php';
    assert_true(function_exists('functErrorHandleDefault'));
});
