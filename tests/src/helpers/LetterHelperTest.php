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

test_run('LetterHelper::range() - single letter', function () {
    $range = LetterHelper::range('A', 'C');
    assert_equals(['A', 'B', 'C'], $range);
});

test_run('LetterHelper::range() - multi letter (using native/polyfill str_increment)', function () {
    $range = LetterHelper::range('Z', 'AB');
    assert_equals(['Z', 'AA', 'AB'], $range);
});
