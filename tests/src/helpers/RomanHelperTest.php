<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\RomanHelper
 */
use anovsiradj\skit\helpers\RomanHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('RomanHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\RomanHelper'));
});
