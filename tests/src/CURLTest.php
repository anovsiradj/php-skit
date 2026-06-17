<?php

/**
 * HowTo & Test: anovsiradj\skit\CURL
 * 
 * Usage:
 * ```php
 * use anovsiradj\skit\CURL;
 * $curl = new CURL('https://example.com');
 * $curl->url('/api/data');
 * // $curl->exec() (omitted here since it's an external call)
 * ```
 */

use anovsiradj\skit\CURL;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('CURL - init and build URL', function () {
    if (!extension_loaded('curl')) {
        Assert::skip('ext-curl not loaded');
    }

    $curl = new CURL('https://dummyjson.com');
    $curl->url('/products/1', ['limit' => 10]);

    Assert::equals('https://dummyjson.com/products/1?limit=10', $curl->url);
});
