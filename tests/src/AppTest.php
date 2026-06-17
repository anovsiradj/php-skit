<?php

/**
 * HowTo & Test: anovsiradj\skit\App
 * 
 * Usage:
 * ```php
 * use anovsiradj\skit\App;
 * class MyApp extends App {}
 * ```
 */

use anovsiradj\skit\App;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('App - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\App'));
});
