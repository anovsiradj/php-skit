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

test_run('App - class exists', function () {
    assert_true(class_exists('anovsiradj\skit\App'));
});
