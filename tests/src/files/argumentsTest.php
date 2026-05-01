<?php

/**
 * HowTo & Test: src/files/arguments.php
 * 
 * Usage:
 * ```php
 * $argv = ['script.php', 'foo=bar', 'baz=1'];
 * $arguments = require 'src/files/arguments.php';
 * // ['foo' => 'bar', 'baz' => '1']
 * ```
 */

test_run('files/arguments.php - parse CLI args', function () {
    global $argv;
    $argvBackup = $argv;

    $argv = ['script.php', 'a=1', 'b=2&c=3'];
    $argumentsEchoExit = false;

    // require file returns the array directly
    $args = require __DIR__ . '/../../../src/files/arguments.php';

    assert_equals(['a' => '1', 'b' => '2', 'c' => '3'], $args);

    // Restore
    $argv = $argvBackup;
});
