<?php

/**
 * HowTo & Test: anovsiradj\skit\spreadsheet\StyleHelper
 * 
 * Usage:
 * ```php
 * use anovsiradj\skit\spreadsheet\StyleHelper;
 * ```
 */

use anovsiradj\skit\spreadsheet\StyleHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('StyleHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\spreadsheet\StyleHelper'));
});
