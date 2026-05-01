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

test_run('Funct - class exists', function () {
    assert_true(class_exists('anovsiradj\skit\Funct'));
});
