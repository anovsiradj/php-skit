<?php

/**
 * HowTo & Test: anovsiradj\skit\Funct
 * 
 * Usage:
 * ```php
 * use anovsiradj\skit\Funct;
 * Funct::loadFileDefault(); // loads error.php
 * ```
 */

use anovsiradj\skit\Funct;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('Funct - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\Funct'));
});
