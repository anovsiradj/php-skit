<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\NumberHelper
 */
use anovsiradj\skit\helpers\NumberHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('NumberHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\NumberHelper'));
});
