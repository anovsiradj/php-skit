<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\LetterHelper
 * 
 * Usage:
 * ```php
 * use anovsiradj\skit\helpers\LetterHelper;
 * $cols = LetterHelper::range('A', 'C');
 * // ['A', 'B', 'C']
 * ```
 */

use anovsiradj\skit\helpers\LetterHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('LetterHelper::range() - single letter', function () {
    $range = LetterHelper::range('A', 'C');
    Assert::equals(['A', 'B', 'C'], $range);
});

Runner::getInstance()->runTest('LetterHelper::range() - multi letter (using native/polyfill str_increment)', function () {
    $range = LetterHelper::range('Z', 'AB');
    Assert::equals(['Z', 'AA', 'AB'], $range);
});
